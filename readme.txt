A platform for managing your own applications on bitrix24 cloud

Apllication works on  _REQUEST[action] parameter (your.domain?action=your_action)
if there is no _REQUEST[action] parameter, it will cause fatal error
Apllication is written in mvc architecture and consists of model view and controller files which are located in app directory

Files naming requriements
	- model must be named like model-application_name.php and must be located in app/model/directory
	- controller must be named like controller-application_name.php and must be located in app/controller/directory
	- view file can be name as you wish and with .tpl extension example: project_edit.tpl

model-application_name.php must be class application_name extends model {}
in controller file there must be  global $b24u; line. it is an object of main model class

html outputs controller controller

in helpers folder are locating auxiliary libraries and classes such as tcpdf, database management class, mail helper class etc.


