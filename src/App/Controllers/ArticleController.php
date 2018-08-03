<?php

/**
 * Article Controller
 *
 * @author Coderberg
 */

namespace App\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Article;

class ArticleController
{
    public function index(Request $request, Application $app)
    {
        $article = new Article();
        
        $data = $article->getAll($request, $app);

        return $app['twig']->render('index.html.twig', $data);
        
    }

    public function article(Request $request, Application $app)
    {
        $article = new Article();
        
        $data = $article->getArticle($request, $app);

        if ($data) {
            // Return article
            return $app['twig']->render('article.html.twig', $data);

        } else {
            if (!$app['debug']) {
                // Return page 404 for users
                return $app['twig']->render('errors/404.html.twig');
            } else {

                // Return all errors for developer
                return $app['twig']->render('article.html.twig', $data);
            }
        }
    }
}
