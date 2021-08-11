<?php

namespace App\Controller;

use mysqli;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConditionTestController extends AbstractController
{
    /**
     * @Route("/condition/test", name="condition_test")
     */
    public function index(): Response
    {

        $unique_character = $this->getNumOfUniqueCharacters('Alabama', 3);
        $transfer = $this->transfer([123, 456]);


        return $this->render('condition_test/index.html.twig', [
            'unique' => $unique_character,
            'transfer' => $transfer,

        ]);
    }

    /*1. PHP

Code a function that returns the number all unique case-insensitive characters that occur >= $n times in a given string.

function getNumOfUniqueCharacters($str, $n) {
	// ...
}

Examples:
getNumOfUniqueCharacters('A1B2C3', 2); // 0
getNumOfUniqueCharacters('A1a1C1', 2); // 2, because A and 1 both occur 2 or more times.
getNumOfUniqueCharacters('Alabama', 3); // 1
*/

    public function getNumOfUniqueCharacters($str, $n): int
    {
        $rez = 0;
        foreach (count_chars(mb_strtolower($str), 1) as $val) {
            if ($val >= $n) {
                $rez++;
            }
        }
        return $rez;

    }


    /*4. PHP

"Carry" is a term of an elementary arithmetic. It's a digit that you transfer to column with higher significant digits when adding numbers.
This task is about getting the sum of all carried digits.
You will receive an array of two numbers, like in the example. The function should return the sum of all carried digits.

function carry($arr) {
	// ...
}

carry([123, 456]); // 0
carry([555, 555]); // 3
carry([123, 594]); // 1

Support of arbitrary number of operands will be a plus:
carry([123, 123, 804]); // 2*/


    public function transfer(array $arr): int
    {


        $split_arr = [];
        foreach ($arr as $value) {
            $split_arr[] = str_split($value);
        }

        $rez = 0;
        for ($i = 0; $i <= count($split_arr); $i++) {
            $sum = array_sum( array_column($split_arr, $i));
            if ($sum > 9) {
                $rez++;
            }
        }
        return $rez;
    }




    /*
6. MySQL

We have the following tables:

Table: branch
Fields:
id int (pk)
name varchar

Table: person
Fields:
id int (pk)
office_id int not null
chief_id int
name varchar
wage float

Please write SQL queries for:

a) Select all people, who get paid more than their direct chiefs
b) Select a list of all offices along with a person with the highest wage in each. if more than one person has the highest wage, display them all. The office should be selected even if it has no people.
c) Select all chiefs, who have exactly one direct subordinate.
d) Select all offices sorted by total wage of people in it, descending.*/
    //---->  не очень понятно условие

    public function query()
    {
        $mysqli = new mysqli("example.com", "user", "password", "database");

        $mysqli->query("    
                                SELECT *
                                FROM Person
                                LEFT JOIN Cheif
                                ON Person.wage > Cheif.wage;
                                ");

        $mysqli->query("    
                                SELECT office_id, MAX(wage)
                                FROM Person
                                GROUP BY office_id ;
                                ");


        $mysqli->query("    
                                SELECT *
                                FROM Person
                                WHERE cheif_id NOT NULL;
                                ");

        $mysqli->query("    
                                SELECT office_id
                                FROM Person
                                ORDER BY wage ;
                                ");

    }

}
