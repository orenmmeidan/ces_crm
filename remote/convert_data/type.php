<?php

/********************************/
/* Code at http://legend.ws/blog/tips-tricks/csv-php-mysql-import/
/* Edit the entries below to reflect the appropriate values
/********************************/
$databasehost = "localhost";
$databasename = "ces_crm";
$databasetable = "oop_leads";
$databaseusername ="yael";
$databasepassword = "tomato";
$fieldseparator = ",";
$lineseparator = "\n";
$csvfile = $argv[1]; //"/home/yael/Downloads/EricExcelFiles/temp.csv";

$fields = "(CompanyName, Description, HotWarmCool, LastContactDate, SalesRep, ContactName, Phone, FaxSecondNumberExt, Email, SecondEmail, Type)";

/********************************/
/* Would you like to add an ampty field at the beginning of these records?
/* This is useful if you have a table with the first field being an auto_increment integer
/* and the csv file does not have such as empty field before the records.
/* Set 1 for yes and 0 for no. ATTENTION: don't set to 1 if you are not sure.
/* This can dump data in the wrong fields if this extra field does not exist in the table
/********************************/
$addauto = 0;
/********************************/
/* Would you like to save the mysql queries in a file? If yes set $save to 1.
/* Permission on the file should be set to 777. Either upload a sample file through ftp and
/* change the permissions, or execute at the prompt: touch output.sql && chmod 777 output.sql
/********************************/
$save = 1;
$outputfile = "/home/yael/Downloads/EricExcelFiles/output.sql";
/********************************/


$con = @mysql_connect($databasehost,$databaseusername,$databasepassword) or die(mysql_error());
@mysql_select_db($databasename) or die(mysql_error());

$lines = 0;
$queries = "";
$linearray = array();

$row = 1;
if (($handle = fopen($csvfile, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if ($row++==1) continue;
        $insertrow='';
        $num = count($data);
        for ($c=0; $c < 11; $c++) {
           $insertrow .=",'" . mysql_real_escape_string($data[$c]) . "'";
        }

    $insertrow = trim($insertrow, ',');

    $query = "insert into $databasetable $fields values($insertrow);";

    $queries .= $query . "\n";

    $rc=mysql_query($query);
    if ($rc==false) echo $query;

}
}
    fclose($handle);

@mysql_close($con);




?>
