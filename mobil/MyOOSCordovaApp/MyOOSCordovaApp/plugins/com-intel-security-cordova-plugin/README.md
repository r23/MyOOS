App Security API
================ 
The App Security API enables the use of security properties and capabilities on the platform, using a new set of API defined for application developers.
You are not required to be a security expert to make good use of the API. Key elements, such as encryption of data and establishments of capabilities, is abstracted and done by the API implementation, for you.
For example
-	Use the API to store (E.g. cache) data locally, using the device non-volatile storage. Data protection/encryption will be done for you by the API implementation 
-	Establish a connection with remote server (E.g. XHR) using a protected channel. SSL/TLS establishment and usage will be done for you by the API implementation

For more information please visit our API documentation @ https://software.intel.com/en-us/app-security-api/api
Additionally please see our demo applications:
	- "MyPrivateNotes":		https://software.intel.com/en-us/xdk/article/my-private-notes-sample
	- "MyPrivatePhotos":	https://software.intel.com/en-us/xdk/article/my-private-photos-sample		

Cordova plugin 
==============

	How to use the plugin
	=====================
	This example is for Android but applicable for iOS and Windows
		1. Create a new Cordova project
			cordova create AppDir com.intel.security AppDir

		2. Navigate to the Cordova project directory
			cd AppDir

		3. Add android to the project
			cordova platform add android

		4. (Optional) Verify that Android was added correctly
			cordova platform

		5. Add the plugin to the project (use a local copy)
			cordova plugin add /PATH/TO/YOUR/LOCAL/COPY

		6. (Optional) Verify that the plugin was added correctly 
			cordova plugin

		7. Build the project
			(Optional) for Windows build instructions please see below
			cordova build 

		8. (Optional) Run the project
			cordova emulate	(SDK emulator)
			-or-
			cordova run (connected device)

	Cordova 5.4.1 Windows build flow
	================================
		
		It is recommended to upgrade the Cordova windows tools, please run "cordova platform update windows 4.2.0"
		
		To build the Windows project per architecture please use the following commands:	
		Windows 8:
			Assuming MSBuild is at "c:\Windows\Microsoft.NET\Framework\v4.0.30319\"
			for x86 --> c:\Windows\Microsoft.NET\Framework\v4.0.30319\MSBuild CordovaApp.Windows80.jsproj /p:Platform=x86 /t:rebuild /p:Configuration=Release
			for x64 --> c:\Windows\Microsoft.NET\Framework\v4.0.30319\MSBuild CordovaApp.Windows80.jsproj /p:Platform=x64 /t:rebuild /p:Configuration=Release
			for ARM --> c:\Windows\Microsoft.NET\Framework\v4.0.30319\MSBuild CordovaApp.Windows80.jsproj /p:Platform=ARM /t:rebuild /p:Configuration=Release
			
		Windows 8.1:
			for x86 --> cordova prepare windows --release --arch=x86 -- --win
						cordova compile windows --release --arch=x86 -- --win
			for x64 --> cordova prepare windows --release --arch=x64 -- --win
						cordova compile windows --release --arch=x64 -- --win
			for ARM --> cordova prepare windows --release --arch=ARM -- --win
						cordova compile windows --release --arch=ARM -- --win

		Windows 10:
			for x86 --> cordova prepare windows --release --arch=x86 -- --appx=uap
						cordova compile windows --release --arch=x86 -- --appx=uap
			for x64 --> cordova prepare windows --release --arch=x64 -- --appx=uap
						cordova compile windows --release --arch=x64 -- --appx=uap
			for ARM --> cordova prepare windows --release --arch=ARM -- --appx=uap
						cordova compile windows --release --arch=ARM -- --appx=uap

						
	How to use the plugin from Visual Studio 2015 (Apache Cordova project):
	=======================================================================
		
		The following steps describe how to create a Cordova based app with App Security API for Windows:
		
		1. Create a new Apache Cordova project from Visual Studio 2015 (Javascript projects type). If you don't have this type you probably not running the latest update of Visual Studio 2015.
		
		2. Add App Security API plugin by:
			a) double click on config.xml file 
			b) choose plugins tab
			c) choose custom and copy the github link of the plugin: https://github.com/AppSecurityApi/com-intel-security-cordova-plugin.git
		
		3. Add your web app content to the www folder.
		
		4. Build the project, again make sure you build for Windows.
	
		
		
		The following steps describe how to create a Cordova based app with App Security API for Android:
		
		1. Create a new Apache Cordova project from Visual Studio 2015 (Javascript projects type). If you don't have this type you probably not running the latest update of Visual Studio 2015.
		
		2. Add App Security API plugin by:
			a) double click on config.xml file 
			b) choose plugins tab
			c) choose custom and copy the github link of the plugin: https://github.com/AppSecurityApi/com-intel-security-cordova-plugin.git
			
		3. Add your web app content to the www folder.
		
		4. Build the project and make sure you choose Android (next to the run debug button). Now you have your app up and running using the plugin for Android OS.
		
		
		
		Note: iOS has not been tested yet but according to the documentation it should be the same as Android with one change- make sure you choose iOS in the build.

Crosswalk Extension
===================		
For Crosswalk extension please see https://github.com/AppSecurityApi/com-intel-security-crosswalk-extension.git
