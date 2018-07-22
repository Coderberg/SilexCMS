<?php

namespace App\Controllers\Admin;

use Silex\Application;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Models\Article;

/**
 * Admin Article Controller
 * 
 * @author Coderberg
 */
class ArticleController
{
    // All articles
    public function index(Request $request, Application $app)
    {
        $article = new Article();
        
        $data = $article->getAllTitles($request, $app);

        return $app['twig']->render('admin/articles/index.html.twig', $data);

    }
    
    // Create article
    public function create(Request $request, Application $app)
    {

        // Build Form      
        $formBuilder = $app['form.factory']->createBuilder();
        $formBuilder->setMethod('post');
        $formBuilder->setAction($app['url_generator']->generate('admin.create_articles'));
        
        // Input title
        $formBuilder->add('title', TextType::class, [
            'label'    => 'Title',
            'trim'     => true,
            'required' => true,
            'constraints' => [
                new Assert\NotBlank()
            ]
        ]);
        
        // Input author
        $formBuilder->add('author', TextType::class, [
            'label'    => 'Author',
            'trim'     => true,
            'required' => true,
            'constraints' => [
                new Assert\NotBlank()
            ]
        ]);
        
        $formBuilder->add('picture', FileType::class, [
            'label'    => 'Picture',
            'required' => true,
            'constraints' => [
                new Assert\Image(), 
                new Assert\NotNull()
            ]
        ]);
        
        // Full text
        $formBuilder->add('text', TextareaType::class, [
            'label'    => 'Text',
            'trim'     => true,
            'required' => true,
            'constraints' => [
                new Assert\NotBlank()
            ]
        ]);
        
        $formBuilder->add('submit', SubmitType::class);
        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        
        // Validation
        if ($form->isSubmitted() && $form->isValid()) {
            
            $input = $form->getData();
            
            // Store and get last id
            $article = new Article();
            $id = $article->create($input, $app);
            
            // Success
            if (ctype_digit( $id )) {
                
                //if (isset($input['picture'])) {
                    
                    //$fileName = md5(uniqid()) . '.' . $input['picture']->guessExtension();
                    $fileName = $id . '.jpg';
                    
                    // Import settings
                    $path = include __DIR__ . '/../../../../config/uploads.php';
                                        
                    $input['picture']->move(
                        $path['full_image_upload_folder'],
                        $fileName
                    );

                //}

                // Alert
                $app['session']->getFlashBag()->add('success', "You successfully created a new article.");

                // Return view
                return $app->redirect($app['url_generator']->generate('admin.articles'));
            }
        }
        
        // Html form
        return $app['twig']->render('admin/articles/create.html.twig', [
            'form' => $form->createView()
        ]);
        
    }
    
    // Update article
    public function update(Request $request, Application $app)
    {
        $article = new Article();
        $data = $article->getArticle($request, $app);
        
        $formBuilder = $app['form.factory']->createBuilder();
        $formBuilder->setMethod('post');
        $formBuilder->setAction($app['url_generator']->generate('admin.articles_update', array('id' => $data['id'])));
        
       // Input title
        $formBuilder->add('title', TextType::class, [
            'label'    => 'Title',
            'trim'     => true,
            'required' => true,
            'data'     => $data['title'],
            'constraints' => [
                new Assert\NotBlank()
            ]
        ]);
        
        // Input author
        $formBuilder->add('author', TextType::class, [
            'label'    => 'Author',
            'trim'     => true,
            'required' => true,
            'data'     => $data['author_name'],
            'constraints' => [
                new Assert\NotBlank()
            ]
        ]);
        
        $formBuilder->add('picture', FileType::class, [
            'label'    => 'Picture',
            'required' => false,
            'constraints' => [
                new Assert\Image()
            ]
        ]);
        
        // Full text
        $formBuilder->add('text', TextareaType::class, [
            'label'    => 'Text',
            'trim'     => true,
            'required' => true,
            'data'     => $data['text'],
            'constraints' => [
                new Assert\NotBlank()
            ]
        ]);
        
        $formBuilder->add('submit', SubmitType::class);
        
        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            // Get POST data
            $input = $form->getData();
            
            // Get current ID from url
            $input['id'] = $request->attributes->get('id');
            
            $article = new Article();
            
            // Check article by ID
            if ($article->getArticle($request, $app)) {
                
                // Update data 
                $article->update($input, $app);
                
                // Update picture
                if ($input['picture']) {
                    
                    $fileName = $input['id']. '.jpg';
                    
                    // Import settings
                    $path = include __DIR__ . '/../../../../config/uploads.php';
                    
                    $fs = new Filesystem();
                    
                    // Remove old picture
                    try {
                        $fs->remove($path['full_image_upload_folder'] . '/' . $fileName);
                        
                    } catch (IOExceptionInterface $e) {
                        
                        echo "An error occurred while removing the old picture. ".$e;
                        
                    }
                    
                    // Upload new picture
                    $input['picture']->move(
                        $path['full_image_upload_folder'],
                        $fileName
                    );
                }

                $app['session']->getFlashBag()->add('success', "You successfully updated article.");
                return $app->redirect($app['url_generator']->generate('admin.articles'));
            } else {
                
                $app['session']->getFlashBag()->add('error', "Something went wrong.");
            }
        }
        
        // Html form
        return $app['twig']->render('admin/articles/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    
    // Delete article
    public function delete(Application $app, Request $request)
    {
        $article = new Article();

        $data = $article->getArticle($request, $app);
        
        if (!$data) {
            $app['session']->getFlashBag()->add('error', "The article you requested doesn't exist.");
            return $app->redirect($app['url_generator']->generate('admin.articles'));
        }
        $fs = new Filesystem();
        try {
            
            // Import settings
            $path = include __DIR__ . '/../../../../config/uploads.php';
            
            $fs->remove($path['full_image_upload_folder'] . '/' . $data['id'] .'.jpg');
            
        } catch (IOExceptionInterface $e) {
            
            echo "An error occurred while removing the old picture. ".$e;
            
        }
        
        // Delete from the database
        $rowCount = $article->delete($data['id'], $app);
        
        // Check if deletion was successful in the database
        if ($rowCount > 0) {
            
            $app['session']->getFlashBag()->add('success', "You successfully deleted the article.");
            
        } else {
            
            $app['session']->getFlashBag()->add('error', "Can't delete an article.");
        }
        
        return $app->redirect($app['url_generator']->generate('admin.articles'));
    }
}
