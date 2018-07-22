<?php

namespace App\Controllers\Admin;

use Silex\Application;
use App\Models\Article;

/**
 * Admin Controller
 * 
 * @author Coderberg
 */
class DashboardController
{
    /**
     * Dashboard
     *
     * @param Application $app Silex application
     */
    public function index(Application $app)
    {
        $article = new Article();
        
        // Count all articles
        $count = $article->getTotalArticles($app);
                
        // Build Array for twig
        $data = ['total_articles' => $count];

        return $app['twig']->render('admin/dashboard.html.twig', $data);
    }
}
