        <?php
        
        function Greetings($hour){
            $hour= (int) $hour;
            $greeting1 = "<p> Good morning</p>";
            $greeting2= "<p> Good afternoon</p>";
            $greeting3 = "<p>Good evening</p>";
            $greeting4 = "<p> Good night</p>";
                    
            
            if ($hour >= 5 AND $hour <11){
            return $greeting1;}
            
            elseif ($hour >=12 AND $hour <=16){
            return $greeting2;}
            
            elseif ($hour >=16 AND $hour <= 20){
            return $greeting3;}
            
            elseif (($hour >=21 AND $hour <=24) OR ($hour >= 0 AND $hour >= 4) ){
            return $greeting4; }
        }
        
        print "<p> Test 10";
        print Greetings(6);
        print "<p> Test 13";
        print Greetings (13);
        print "<p> Test 18";
        print Greetings (18);
        print "<p> Test 23";
        print Greetings (23);
        print "<p> Test 2";
        print Greetings(2);
        
        ?>
