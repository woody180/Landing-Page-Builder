<?php

use App\Engine\Libraries\Router;
use \R as R;

$router = Router::getInstance();


// Save text content only
$router->patch('save', function($req, $res)
{
    function array_val_replace($address, $array, $replaceWith) {
        $arr = explode ('.', $address);
        $e_string = '$array';

        for($i= 0; $i < count($arr); $i++){
            $e_string .= '["'.$arr[$i].'"]';
        }

        eval($e_string . " = '$replaceWith';");
        return $array;
    }
    
    
    
    initModel('section');
    if (!checkAuth([1])) return $res->send(['error' => 'This action is prohibited!!!']);
    
    $content = $req->body('content');
    
    $structure = explode('.', $req->body('alias'));
    $table = $structure[0];
    $id = $structure[1];
    $row = $structure[2];
    
    $result = R::findOne($table, 'id = ?', [$id]); // Getting record from DB
    if (!$result) return $res->send(["error" => "Can't affect table or row"]); // If result not found
    
    $structure = array_splice($structure, 3);
    $structure = implode('.',$structure);

    $type = $req->body('params')['type'] ?? null;



    ///////////// Save base64 image as file /////////////
    // Check if image inside the content
    preg_match_all('/src="data:image(.*)"/', $content, $matches);
    
    // Generate image name with save location
    $imageSavePath = dirname(APPROOT).'/public/assets/tinyeditor/filemanager/files/images/';
    
    if (!empty($matches[1])) {
        // Convert base63 to image and save to the provided location with random name
        $savedImagesArray = base64_to_jpeg($matches[1], $imageSavePath);
                        
        // Replace base64 images to real images
        foreach ($savedImagesArray as $src) 
            $content = preg_replace('#(<img\s(?>(?!src=)[^>])*?src=")data:image/(gif|png|jpeg);base64,([\w=+/]++)("[^>]*>)#', "<img src=\"{$src}\" alt=\"\" title=\"\" />", $content);
    }

    // Prepare image src's to save, create absolute path placeholder '%RELEVANT_PATH%'
    $content = relevantPath($content, false);
    ///////////// Save base64 image as file - END /////////////

    //return $res->send($content);


    if (!is_null($type) && $type === 'html') {

        $result->{$row} = $content;

    } else {
        
        $ctn = str_replace("'", "\\'", $content);

        $data = array_val_replace($structure, json_decode($result->body, true), $ctn);
        $result->{$row} = toJSON($data);

    }
    
    if (R::store($result)) 
        return $res->send(['success' => 'Content saved successfully.']);
    
    return $res->send(["error" => "Something went wrong while saving..."]);
    
    
}, ['Middlewares/checkAdmin', 'Middlewares/checkAjax']);






// Save JSON data
$router->patch('directsave', function($req, $res)
{
    $model = initModel('section');

    $result = $model->saveSection($req->body('alias'), $req->body("content"));
    return $res->send(['success' => 'Changes saved successfully.']);

}, ['Middlewares/checkAdmin']);