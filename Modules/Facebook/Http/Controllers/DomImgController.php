<?php

namespace Modules\Facebook\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\HuongDanVien;
use Illuminate\Support\Facades\Artisan;

class DomImgController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->saveInfoQueue('http://www.huongdanvien.vn/index.php/guide/cat/05/1');
    }

    public function saveInfoQueue($currentUrl) {
        // Begin proess queue and save next queue */
        dispatch(function () use ($currentUrl) {
            $html = new \Htmldom($currentUrl);
            $contactItem = $html->find('.contact-item');

            if (empty($contactItem))
                return;

            // Make next url
            $arrUrl = explode ('/', $currentUrl);
            $arrUrl[count($arrUrl) - 1]++;
            $nextUrl = implode($arrUrl, '/');
//            $queueName = ' --queue=' . $arrUrl[count($arrUrl) - 1];
//            $this->saveInfoQueue($nextUrl);

            $arrInsert = [];
            $curl = curl_init();
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

            foreach($contactItem as $key => $item) {
                $arrInsert[$key]['type_card'] = trim($item->find('.contact-item-left .row div', 0)->innertext) ?? '';
                $arrInsert[$key]['img'] = $item->find('.contact-item-left img', 0)->src ?? '';
                $arrInsert[$key]['name'] = $item->find('.contact-item-right .row', 0)->find('td', 1)->innertext ?? '';
                $arrInsert[$key]['card_number'] = $item->find('.contact-item-right .row', 1)->find('td', 1)->innertext ?? '';
                $arrInsert[$key]['expiry_date'] = $item->find('.contact-item-right .row', 2)->find('td', 1)->innertext ?? '';
                $arrInsert[$key]['issue_place'] = $item->find('.contact-item-right .row', 3)->find('td', 1)->innertext ?? '';
                $arrInsert[$key]['experience_util_now'] = $item->find('.contact-item-right .row', 5)->find('td', 1)->innertext ?? '';

                // Save img on archive if not should update on own server
                if($arrInsert[$key]['img']) {
                    curl_setopt($curl,CURLOPT_URL,'http://web.archive.org/save/' . $arrInsert[$key]['img']);
                    curl_exec($curl);
                    curl_setopt($curl,CURLOPT_URL,'http://archive.org/wayback/available?url=' . $arrInsert[$key]['img']);
                    $resArchive = json_decode(curl_exec($curl), true);
                    if ($closet = $resArchive['archived_snapshots']['closest'] ?? '') {
                        $arrInsert[$key]['img'] = str_replace($closet['timestamp'], $closet['timestamp'] . 'if_', $closet['url']);
                    }
//                    else {
//                        // Save on server
//                        $arrInsert[$key]['img'] = '';
//                    }
                }
                HuongDanVien::updateOrCreate(['card_number' => $arrInsert[$key]['card_number']], $arrInsert[$key]);
            }
            sleep(60);
            $this->saveInfoQueue($nextUrl);
        });
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
