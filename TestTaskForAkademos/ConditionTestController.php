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

        $ar_revers = [];
        foreach ($split_arr as $value2) {
            $ar_revers[] = array_reverse($value2);
        }

        $arr_count = [];
        $carry = 0;
        $remainder = 0;
        for ($i = 0; $i < count(max($ar_revers)); $i++) {
            foreach ($ar_revers as $row) {
                foreach ($row as $key => $value) {
                    if ($key === $i) {
                        $arr_count[$key][] = $value;
                    }
                }
            }
            if (array_sum($arr_count[$i]) > 9) {
                $remainder = 1;
            }

            $carry += intdiv(array_sum($arr_count[$i]) + $remainder, 10);
            $arr_count = [];
        }

        return $carry;
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

    public function query()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = new mysqli("", "", "", "");


//        $mysqli->query("CREATE TABLE IF NOT EXISTS branch (
//    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
//    name VARCHAR(255)
//                    ) ENGINE=InnoDB CHARSET=utf8;
//                    ");


//        $mysqli->query("CREATE TABLE IF NOT EXISTS person (
//    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
//    office_id INT NOT NULL,
//    chief_id INT,
//    name VARCHAR(255),
//    wage FLOAT
//                    ) ENGINE=InnoDB CHARSET=utf8;
//                    ");

//        $mysqli->query("INSERT INTO branch ( id, name) VALUES
//    (1, 'Kyiv'),
//    (2, 'Odessa'),
//    (3, 'Dnipro'),
//    (4, 'Harkiv')
//    ");

//                $mysqli->query("INSERT INTO person (id, office_id, chief_id, name, wage) VALUES
//    (1, 4, NULL, 'CTO Zhorik', 10000),
//    (2, 1, 1, 'Team Lead Vasya', 5000),
//    (3, 1, 2, 'Junior Dev Petya', 1000),
//    (4, 1, 2, 'Middle Dev Sasha', 3000),
//    (5, 1, 2, 'Senior System Architect Anton', 8000),
//    (6, 2, 1, 'Team Lead Igor', 3500),
//    (7, 2, 6, 'Middle Dev Lyolik', 3500),
//    (8, 2, 6, 'Middle Dev Bolik', 3500),
//    (9, 4, 6, 'Team Lead Tolik', 4000),
//    (10, 4, 9, 'Middle Dev Lexa', 3500);
//    ");


        //  a) Select all people, who get paid more than their direct chiefs

        $mysqli->query("  SELECT  person.name, person.wage
                                        FROM person
                                        JOIN person AS chief
                                            ON chief.id = person.chief_id
                                        WHERE person.wage > chief.wage;
                                        ");


        //b) Select a list of all offices along with a person with the highest wage in each.
        // if more than one person has the highest wage, display them all.
        // The office should be selected even if it has no people.


        $mysqli->query(" SELECT branch.*, person.*
                                        FROM branch
                                        LEFT JOIN person
                                            ON person.office_id = branch.id
                                        LEFT JOIN (
                                            SELECT person.office_id, MAX(person.wage) AS max_wage FROM person  GROUP BY person.office_id
                                        ) AS s ON s.office_id = person.office_id
                                        WHERE person.wage = s.max_wage;
                                    
                                    ");


        //c) Select all chiefs, who have exactly one direct subordinate.

        $mysqli->query("SELECT chief.* 
                                        FROM person 
                                        JOIN person AS chief
                                            ON chief.id = person.chief_id
                                        WHERE chief.id IN (
                                            SELECT chief_id FROM person GROUP BY chief_id HAVING COUNT(*) = 1 
                                        )
                                                                 
                                        ");


        //d) Select all offices sorted by total wage of people in it, descending.

        $mysqli->query("SELECT *, (
                                        SELECT SUM(wage) 
                                        FROM person
                                        WHERE person.office_id = branch.id ) AS wage  
                                        FROM branch  
                                            ORDER BY wage DESC
                                        ");

    }

}
