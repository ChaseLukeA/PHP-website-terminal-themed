<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Printer List</title>
        <meta author="Luke A Chase">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
   </head>
    <body>

        <?php

        echo "<p>printers</p>"
           . "Type <span class=\"coded\">printers --help</span> for more information"
           . "<p>printers --help</p>"
           . "USAGE:<br>"
           . "<span class=\"coded\">printers --openfile &lt;FILENAME&gt;</span> :<br>"
           . "&nbsp; enter file name to process printer information from<br>"
           . "<span class=\"coded\">printers --closefile</span> :<br>"
           . "&nbsp; closes currently open printer file<br>"
           . "<span class=\"coded\">printers --filestatus</span> :<br>"
           . "&nbsp; checks open/close status of current file<br>"
           . "<span class=\"coded\">printers --listall</span> :<br>"
           . "&nbsp; lists all currently active printers<br>"
           . "<span class=\"coded\">printers --averagepagecount</span> :<br>"
           . "&nbsp; computes average number of pages for all active printers<br>";
        
        $filename = $_POST["submit"] && $_POST["filename"] != "" ? $_POST['filename'] : "";

        echo "\n<form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">"
           . "  <p>printers --openfile "
           . "    <input type=\"text\" name=\"filename\" value=\"$filename\""
           . "     placeholder=\"enter file name here\">"
           . "    <input type=\"submit\" name=\"submit\">"
           . "  </p>"
           . "</form>";

        if (isset($_POST["submit"])) {

            // best practice to declare the file to use as variable
            // instead of using it directly in function calls
            $file = $_POST["filename"];
            $averagePageCount = 0;
            $fileFormatted = "<span class=\"coded\">$file</span>";

            
            // declaring $fp in "if" statement since it will
            // already return false if $file doesn't exist
            if ($fp = fopen($file, "r")) {
                
                echo "The file $fileFormatted opened successfully";
                
                // declaring $printer in "if" statement since
                // it will return false when end of file reached
                while ($printer = fgets($fp)) {

                    if ($printer != "") {
                        
                        List($pName, $printerType, $numPage) = explode(":", $printer);

                        $pType[$pName] = $printerType;
                        $pages[$pName] = $numPage;

                    }
                    
                }

                echo "<p>printers --listall</p>";

                foreach ($pType as $pName => $printerType) {

                    echo "<span class=\"output\">" . $pName . " is a "
                       . $printerType . " and currently has a page count of "
                       . $pages[$pName] . " pages</span><br>";

                    $averagePageCount += $pages[$pName];

                }

                echo "<p>printers --averagePageCount</p>";

                echo "<span class=\"output\">The average page count for all printers is "
                . sprintf("%.1f", $averagePageCount / count($pages)) . "</span>";
                
                echo "<p>printers --filestatus</p>"
                   . "The file $fileFormatted is " . (is_resource($fp) ? "open" : "closed")
                   . "<p>printers --closefile</p>";


                if (fclose($fp)) {

                    echo "The file $fileFormatted is " . (is_resource($fp) ? "open" : "closed");

                }
                else {

                    echo "ERROR:<br>" . "The file $fileFormatted was unable to close.";

                }


            }
            else {  // file does not exist
                
                echo "The file $fileFormatted does not exist<br><br>"
                   . "HINT: try <span class=\"coded\">printers.txt</span>";
                
            }

                echo "<p><a href=\"index.php\">back</a></p>";

        }

        ?>
    </body>
</html>
