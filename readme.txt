A platform for managing your own applications on REST api on bitrix24 cloud

Apllication works on  _REQUEST[action] parameter (your.domain?action=your_action)
if there is no _REQUEST[action] parameter, it will cause fatal error
Apllication is written in mvc architecture and consists of model view and controller files which are located in app directory

Files naming requriements
	- model must be named like model-application_name.php and must be located in app/model/ directory
	- controller must be named like controller-application_name.php and must be located in app/controller/ directory
	- view file can be named as you wish and with .tpl extension. Example: project_edit.tpl . View files must be located in app/views directory. You can create your oen directory in views/ but in that case you must add your directory to file name when calling model::load_view method (example: your view .tpl file is located in app/views/myapp/myapp.tpl you must call load_view in this way model::load_view(myapp/myapp)). see model::load_view method

class model_application_name in  model-application_name.php must extend main model class - class model_application_name extends model {}
class controller-application_name in controller-application_name.php must extend main controller class - class controller_application_name extends controller {}

html outputs by controller::output method 

in helpers folder are locating auxiliary libraries and classes such as tcpdf, database management class, mail class etc.
helpers can be loaded by model::load_helper method

bitrix24 REST api documentation https://dev.1c-bitrix.ru/rest_help/
learning courses from 1c-bitrix https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=99


