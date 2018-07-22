<?php

namespace App\Models;

use App\Helpers\Paginator;

/**
 * Article Model
 *
 * @author Coderberg
 */

class Article
{
    // Articles per page
    const SHOW_BY_DEFAULT = 6;
    
    // Store
    public function create($param, $app)
    {
        $sql = 'INSERT INTO articles '
                . '(title, short_text, text, author_name)'
                . 'VALUES '
                . '(:title, :short_text, :text, :author)';

        // Generate short text
        $short_text = strip_tags(mb_substr($param['text'],0,150))."...";
        
        $stmt = $app['db']->prepare($sql);
        $stmt->bindValue("title", $param['title']);
        $stmt->bindValue("short_text", $short_text);
        $stmt->bindValue("text", $param['text']);
        $stmt->bindValue("author", $param['author']);

        if ($stmt->execute()) {

            return $app['db']->lastInsertId();
        }

    }

    // Get all articles
    public function getAll($request, $app)
    {
        $limit = self::SHOW_BY_DEFAULT;
        
        $page = $request->attributes->get('page');

        if ($page != '') {
            
            $page = intval($page);
            
        } else {
            
            $page = 1;
        }
        
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
        
        $sql = "SELECT * FROM articles ORDER BY date DESC "
                . "LIMIT ".(int)$limit." "
                . "OFFSET ".(int)$offset;
        
        $articles = $app['db']->fetchAll($sql);
        
        // Get total articles
        $total = self::getTotalArticles($app);
        
        // Pagination args
        $args = ['current_page' => $page, 
                    'total_items' => $total, 
                    'per_page' => $limit, 
                    'cur_uri' => $app['url_generator']->generate('articles')];
        
        // Get html pagination
        $pagination = Paginator::get($args);
        
        $data = ['articles' => $articles, 'page' => $page, 'total' => $total, 'pagination' => $pagination];
        
        return $data;
    }
    
    // Get articles list for editing
    public function getAllTitles($request, $app)
    {
        $limit = self::SHOW_BY_DEFAULT;
        
        $page = $request->attributes->get('page');

        if ($page != '') {
            
            $page = intval($page);
            
        } else {
            
            $page = 1;
        }
        
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
        
        $sql = "SELECT id, title, author_name FROM articles ORDER BY date DESC "
                . "LIMIT ".(int)$limit." "
                . "OFFSET ".(int)$offset;
        
        $articles = $app['db']->fetchAll($sql);
        
        // Get total articles
        $total = self::getTotalArticles($app);
        
        // Pagination args
        $args = ['current_page' => $page, 
                    'total_items' => $total, 
                    'per_page' => $limit, 
                    'cur_uri' => $app['url_generator']->generate('admin.articles')];
        
        // Get html pagination
        $pagination = Paginator::get($args);
        
        $data = ['articles' => $articles, 'page' => $page, 'total' => $total, 'pagination' => $pagination];
        
        return $data;
    }

    // Get single article by id
    public function getArticle($request, $app)
    {
        $id = $request->attributes->get('id');

        $sql = "SELECT * FROM articles WHERE id = ?";
        $article = $app['db']->fetchAssoc($sql, array((int) $id));
      
        return $article;
    }
    
    // Returns total articles
    public static function getTotalArticles($app)
    {
        $sql = "SELECT count(id) AS count FROM articles";

        $count = $app['db']->fetchAssoc($sql);

        return $count['count'];
    }
    
    // Update article
    public function update(array $input, $app)
    {
        if (isset ($input['id'])) {

            $sql = 'UPDATE articles SET '
                    . 'title = :title, '
                    . 'short_text = :short_text, '
                    . 'text = :text, '
                    . 'author_name = :author '
                    . 'WHERE id = :id';

            // Generate short text
            $short_text = html_entity_decode(strip_tags(mb_substr($input['text'],0,150))."...");
            //$short_text = substr($input['text'],0,150)."...";

            $stmt = $app['db']->prepare($sql);
            $stmt->bindValue("title", $input['title']);
            $stmt->bindValue("short_text", $short_text);
            $stmt->bindValue("text", $input['text']);
            $stmt->bindValue("author", $input['author']);
            $stmt->bindValue("id", $input['id']);

            if ($stmt->execute()) {
                return true;

            } else {
                return false;
            }
        }
    }
    
    // Delete article
    public function delete(int $id, $app)
    {
        $count = 0;
        
        $sql = "DELETE FROM articles "
                . "WHERE id='" . $id . "' "
                . "LIMIT 1";
        
        $del = $app['db']->prepare($sql);
        $del->execute();

        /* Return number of rows that were deleted */
        $count = $del->rowCount();
        
        return (int)$count;
    }
}
