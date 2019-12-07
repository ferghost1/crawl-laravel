<?php

namespace Modules\Facebook\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class FacebookController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('facebook::index');
    }

    /**
     * Configuration and setup Facebook SDK
     * @return Response
     */
    public function getPost()
    {
        /*
       * Configuration and setup Facebook SDK
      */
        $appId 			= '153527078587885'; //Facebook App ID
        $appSecret 		= 'edf7dac04c9a42ab0f023fd43c9627cf';
        $redirectURL 	= route('facebook-scan'); //Callback URL
        $fbPermissions 	= array('email');  //Optional permissions

        $fb = new Facebook(array(
            'app_id' => $appId,
            'app_secret' => $appSecret,
            'default_graph_version' => 'v2.10',
        ));

        // Get redirect login helper
        $helper = $fb->getRedirectLoginHelper();

        // Try to get access token
        try {
            if(isset($_SESSION['facebook_access_token'])){
                $accessToken = $_SESSION['facebook_access_token'];
            }else{
                $accessToken = $helper->getAccessToken();
            }
        } catch(FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        /**
         * callback_url
         * token
         * limit
         */
        $config['callback_url']         =   '&callback=processResult&limit=5'; //
        $config['token']         		= 	'EAAAAUaZA8jlABAEVyZAF1mMVRZBNOxj03BNQugQiHwYRKRXIKgvZAWzAsg8YPMhQbnHgLhzZBZB231YPleMcpX4ZBrgZBmiIUHdtjGHGR48BWdkFoF4gsWPYkmRZACBPZCxe4j6cRkuyy8oUdMaqICHdUJZAmLuay92RGTgAoImUPuAhAZDZD';
        $config['limit']         		= '10'; //số post load về
        $graph_url = "https://graph.facebook.com/v2.10/2024717370942015/feed?fields=id,full_picture,from,caption,created_time,description,message,updated_time,likes,type,source&limit=".$config['limit']."&access_token=".$config['token']."";
        $feed = json_decode(file_get_contents($graph_url));
//        $feed_paging = json_decode(file_get_contents($graph_url));

        foreach($feed->data as $key => $data)
        {

          $tung =explode('?',$data->full_picture)[0];
            if(empty($data->full_picture)) var_dump($key);
           var_dump($feed->data[0]->full_picture);
        }
        //return 'done';
    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('facebook::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('facebook::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('facebook::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
