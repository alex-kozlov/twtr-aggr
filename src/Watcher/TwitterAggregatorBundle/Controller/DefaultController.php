<?php

namespace Watcher\TwitterAggregatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Twitter\OAuth2\Consumer;
use TwitterOAuth\TwitterOAuth;

class DefaultController extends Controller
{
    public function indexAction()
    {
        //echo $this->container->getParameter('watcher_twitter_aggregator_consumer_key');
        $client = new \Buzz\Browser(new \Buzz\Client\Curl());
        $consumer_key = $this->container->getParameter('watcher_twitter_aggregator_consumer_key');
        $consumer_secret = $this->container->getParameter('watcher_twitter_aggregator_consumer_secret');
        $access_token = $this->container->getParameter('watcher_twitter_aggregator_access_token');
        $access_secret = $this->container->getParameter('watcher_twitter_aggregator_access_secret');
        
        $config = array(
            'consumer_key' => $consumer_key,
            'consumer_secret' => $consumer_secret,
            'oauth_token' => $access_token,
            'oauth_token_secret' => $access_secret,
            'output_format' => 'array'
        );

        $tw = new TwitterOAuth($config);
                
        $response = $tw->get('search/tweets', array('q' => '#ЮридическийФорум'));
        
        $tweets = $response['statuses'];
        echo '<pre>'; print_r($config); echo '</pre>';
        
        foreach ($tweets as $tweet) {
            $response = $tw->post('statuses/retweet/' . $tweet['id_str'], array());
        }     
        
        return $this->render('WatcherTwitterAggregatorBundle:Default:index.html.twig', array('result' => count($tweets)));
    }
}
