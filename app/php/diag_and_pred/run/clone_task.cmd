git clone --progress --recursive https://github.com/apache/commons-math C:\new_users\rotemb271\math\rootGit\commons-math 2>diag_and_pred\run\proj.log
cd C:\new_users\rotemb271\math\rootGit\commons-math
git tag>\tagList.txt
dir /s /b *pom.xml >diag_and_pred\run\pomList.txt
cd C:\xampp\htdocs\secure\in\testforend\app\php
php -f index.php trigger rotemb271 math check_clone >diag_and_pred\run\check_clone.log