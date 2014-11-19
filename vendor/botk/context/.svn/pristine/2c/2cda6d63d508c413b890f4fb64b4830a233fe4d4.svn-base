<?php
require '../vendor/autoload.php';

use BOTK\Context\Context as CX;
use BOTK\Context\ContextNameSpace as V;

// Force iniDir to this dir
$_ENV['BOTK_CONFIGDIR'] = '.';

function test($ns,$prompt,$param,$default=null,$validator=null,$sanitizer=null)
{
    static $cx=null; if (is_null($cx)) $cx= new CX;
    echo "<dt>Get $param with $prompt: </dt><dd>";
    try {
        print_r($cx->ns($ns)->getValue($param,$default,$validator,$sanitizer));
    } catch ( Exception $e ){
        echo $e->getMessage();
    }
    echo '</dd>'; 
}
?>
<html>
<head>
    <title>Test sanitizing and validate context variables</title>
    <link rel="stylesheet" type="text/css" href="http://www.w3.org/StyleSheets/TR/base" />
    <link rel="stylesheet" type="text/css" href="http://ontology.it/tools/bofw/doc.css">

</head>
<body>
    <h1>Try call this script with ....</h1>
    <ul>
        <li><a href='?par1=yes&par2=12'>?par1=yes&par2=12</a></li>
        <li><a href='?par1=1&par2=1234'>par1=1&par2=1234</a></li>
        <li><a href='?par1=1'>just par1=1 (par2 will use the default value 1)</a></li>
        <li>... any combination you want.</li>
    </ul>  
    <article>
        <h2>script result:</h2>
        <dl>
<?php
// Mandatory par1
test(INPUT_GET, 'default filter',             'par1');
test(INPUT_GET, 'ENUM (yes|no)',              'par1', V::MANDATORY, V::ENUM('yes|no'));
test(INPUT_GET, 'FILTER_VALIDATE_INT',        'par1', V::MANDATORY, FILTER_VALIDATE_INT,FILTER_SANITIZE_NUMBER_INT);
test(INPUT_GET, 'FILTER_VALIDATE_INT (0-2)',  'par1', V::MANDATORY, array(
                                                         'filter'=>FILTER_VALIDATE_INT, 
                                                         'options'=> array('min_range' => 0, 'max_range' => 2) 
                                                         ),FILTER_SANITIZE_NUMBER_INT);

// Optional par2  
test(INPUT_GET, 'Default',                    'par2', 1);
test(INPUT_GET, 'ENUM (1|0)',                 'par2', 1, V::ENUM('1|0'));
test(INPUT_GET, 'FILTER_VALIDATE_INT',        'par2', 1, FILTER_VALIDATE_INT,FILTER_SANITIZE_NUMBER_INT);
test(INPUT_GET, 'FILTER_VALIDATE_INT (0-2)',  'par2', 1, array(
                                                         'filter'=>FILTER_VALIDATE_INT, 
                                                         'options'=> array('min_range' => 0, 'max_range' => 2) 
                                                         ),FILTER_SANITIZE_NUMBER_INT);

// Mandatory var1
test('sample', 'default filter',             'var1');
test('sample', 'ENUM (yes|no)',              'var1', V::MANDATORY, V::ENUM('yes|no'));
test('sample', 'FILTER_VALIDATE_INT',        'var1', V::MANDATORY, FILTER_VALIDATE_INT,FILTER_SANITIZE_NUMBER_INT);
test('sample', 'FILTER_VALIDATE_INT (0-2)',  'var1', V::MANDATORY, array(
                                                         'filter'=>FILTER_VALIDATE_INT, 
                                                         'options'=> array('min_range' => 0, 'max_range' => 2) 
                                                         ),FILTER_SANITIZE_NUMBER_INT);

// Optional var2  
test('sample', 'Default',                    'var2', 1);
test('sample', 'ENUM (1|0)',                 'var2', 1, V::ENUM('1|0'));
test('sample', 'FILTER_VALIDATE_INT',        'var2', 1, FILTER_VALIDATE_INT);
test('sample', 'FILTER_VALIDATE_INT (0-2)',  'var2', 1, array(
                                                         'filter'=>FILTER_VALIDATE_INT, 
                                                         'options'=> array('min_range' => 0, 'max_range' => 2) 
                                                         ),FILTER_SANITIZE_NUMBER_INT);

?>
        </dl> 
    </article>
</body>
</html>        
                                                         