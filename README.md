# Remote camera control

> **Note:** This shows an example connection with a Sony a6000 (ILCE-6000). Other cameras may have different URLs and menus.
The source code is written in php to take pictures with the camera by using the API and different curl requests. The following lines shall help to get a better overview.

## Setting up 
All you need to start is to connect your computer with the Wi-Fi network of your camera. To do so: 

1. Navigate to Applicaton List 
2. Select Smart Remote Embedded (You should see the Wi-Fi SSID and password to connect to on the camera screen)
3. Connect to the indicated network from your computer

> Once the computer is connected, Smart Remote on the camera should show "Connecting..." It will continue to wait while you setup the rest of the connection.

## Aviable Methods to work with
By running the following request in the terminal, you get an overview of all possible methods you can use to work with the camera

```
$ curl http://192.168.122.1:8080/sony/camera --data-ascii '{"method":"getAviableApiList", "params":[], "id":1, "version":"1.0"}' 
```

##### List of the methods
```
"getVersions","getMethodTypes","getApplicationInfo","getAvailableApiList","getEvent","actTakePicture",
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
"setWhiteBalance","getWhiteBalance","getSupportedWhiteBalance","getAvailableWhiteBalance"
```

## How to start
To use any kind of method it is important to start with the method ***'startRecMode'***, otherwise you will get an error output that the method 'actTakePicture' for example is not aviable. 
This makes the camera operate in remote shooting mode.

## How to take pictures
Once in recording mode, you can start taking pictures by typing the following command in the terminal

```
$ curl http://192.168.122.1:8080/sony/camera --data-ascii '{"method":"actTakePicture", "params":[], "id":1, "version":"1.0"}' 
```


The code in the file cameraRequest.php will take photos by using the method ***actionCameraRequest***


## Copyright / License / Acknowledgements
- https://github.com/micolous/gst-plugins-sonyalpha by Michael Farrell https://github.com/micolous 
- https://www.dpreview.com/forums/post/53923132 by Derek Che
- Camera Remote API by Sony
