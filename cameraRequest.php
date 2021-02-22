<?php
//Sony Alpha 6000
function setMethod($methodName = "startRecMode") {
    return $json_data = '{
        "method":' . $methodName . ', 
        "params":[], 
        "id":1, 
        "version":"1.0"
    }';
}

function activateCameraRequest($methodName = 'startRecMode'){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"http://192.168.122.1:8080/sony/camera/");
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, setMethod($methodName));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    
    //execute
    curl_exec($ch);
    curl_close ($ch);
}
  
function actionCameraRequest($methodName = "actTakePicture"){
    activateCameraRequest();
    sleep(5);
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"http://192.168.122.1:8080/sony/camera/");
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, setMethod($methodName));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    
    //execute
    $server_output = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($server_output, 0, $header_size);
    $body = substr($server_output, $header_size);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);
    $json_response = json_decode($body,TRUE);
    
    $result = array();
    if(is_array($json_response['result'])){
        foreach($json_response['result'] as $value){
            $result = $value;
        }

        if($methodName == "actTakePicture"){
            $result = $result[0];   
            $explode_filename = explode("/", $result);
            $filename = $explode_filename[4];

            $filePath = 'assets/jpgs/' . $filename;
            $fp = fopen($filePath, 'w');
            $ch = curl_init($result);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
        }

        return $result; 

    }else{
        return $json_response;
    }
}

function startLivestream($methodName = "startLiveview"){
    activateCameraRequest();
    sleep(5);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"http://192.168.122.1:8080/sony/camera/");
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, setMethod($methodName));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    
    //execute
    curl_exec($ch);
    curl_close ($ch);

    $command = "gst-launch-1.0 souphttpsrc location=http://192.168.122.1:8080/liveview/liveviewstream \
    ! jpegparse ! jpegdec ! videoconvert ! videoscale ! videorate ! video/x-raw,width=640,height=480,framerate=1/1 ! autovideosink";
    return shell_exec($command);

    //stopLivestream();
}

function stopLivestream($methodName = "stopLiveview"){

    //activateCameraRequest();
    sleep(5);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"http://192.168.122.1:8080/sony/camera/");
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, setMethod($methodName));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    
    //execute
    $server_output = curl_exec($ch);
    curl_close ($ch);
}

echo "<pre>";
startLivestream();

/*

Reihenfolge Livestream
$ curl -d '{"method": "startRecMode","params": [],"id": 1,"version": "1.0"}' http://192.168.122.1:8080/sony/camera; echo ''
{"id":1,"result":[0]}

$ curl -d '{"method": "startLiveview","params": [],"id": 1,"version": "1.0"}' http://192.168.122.1:8080/sony/camera; echo ''
{"id":1,"result":["http://192.168.122.1:8080/liveview/liveviewstream"]}

gst-launch-1.0 souphttpsrc location=http://192.168.122.1:8080/liveview/liveviewstream \
         ! jpegparse ! jpegdec ! videoconvert ! videoscale ! videorate ! video/x-raw,width=640,height=480,framerate=1/1 ! autovideosink
*/

/*
Liste der Methoden für Sony Alpha 6000: 

["getVersions","getMethodTypes","getApplicationInfo","getAvailableApiList","getEvent","actTakePicture", "startRecMode",
"stopRecMode","startLiveview","stopLiveview","startLiveviewWithSize",
"setSelfTimer","getSelfTimer","getAvailableSelfTimer","getSupportedSelfTimer",
"setExposureMode","getAvailableExposureMode","getExposureMode","getSupportedExposureMode",
"setExposureCompensation","getExposureCompensation","getAvailableExposureCompensation","getSupportedExposureCompensation",
"getFNumber","getAvailableFNumber","getSupportedFNumber",
"setIsoSpeedRate","getIsoSpeedRate","getAvailableIsoSpeedRate","getSupportedIsoSpeedRate",
"getLiveviewSize","getAvailableLiveviewSize","getSupportedLiveviewSize",
"setPostviewImageSize","getPostviewImageSize","getAvailablePostviewImageSize","getSupportedPostviewImageSize",
"setProgramShift","getSupportedProgramShift",
"setShootMode","getShootMode","getAvailableShootMode","getSupportedShootMode",
"getShutterSpeed","getAvailableShutterSpeed","getSupportedShutterSpeed",
"setTouchAFPosition","getTouchAFPosition",
"setWhiteBalance","getWhiteBalance","getSupportedWhiteBalance","getAvailableWhiteBalance"]


*/

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['takePictureRequest'])){
    $url_browser = actionCameraRequest(); 
    sleep(1);
    echo "<script>
        if(confirm('Foto im Browser anzeigen?')){
            window.location.replace('" . $url_browser . "');}
        </script>";
}
if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['liveStreamRequest'])){
    print_r('Klappt noch nicht');
    //activateLivestream();
    //shell_exec mit dem entsprechenden Command wird nicht aufgerufen... andere Commands klappen aber...
}

?>


<link rel="stylesheet" href="style.css">

<div>
<h1>Sony Alpha 6000</h1>

    <table align="center">
        <tr>
            <td>
                <form action="cameraRequest.php" method="post">
                    <input type="submit" name="takePictureRequest" value="Foto machen" />
                </form>
            </td>
        </tr>

        <tr>
            <td>
                <form action="cameraRequest.php" method="post">
                    <input type="submit" name="liveStreamRequest" value="Livestream starten" />
                </form>
            </td>
        </tr>
    </table>
</div>