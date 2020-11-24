<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/
use Gibbon\Services\Format;
use Gibbon\Module\Alumni\AlumniGateway;

include '../../gibbon.php';

$alumniAlumnusID = $_GET['alumniAlumnusID'] ?? '';

$URL = $gibbon->session->get('absoluteURL').'/index.php?q=/modules/'.getModuleName($_POST['address'])."/alumni_manage_edit.php&alumniAlumnusID=$alumniAlumnusID&graduatingYear=".$_GET['graduatingYear'];

if (isActionAccessible($guid, $connection2, '/modules/Alumni/alumni_manage_edit.php') == false) {
    //Fail 0
    $URL .= '&return=error0';
    header("Location: {$URL}");
} else {
    //Proceed!
    //Check if alumniAlumnusID specified
    if (empty($alumniAlumnusID)) {
        //Fail1
        $URL .= '&return=error1';
        header("Location: {$URL}");
    } else {
        $alumniGateway = $container->get(AlumniGateway::class);
        
        $alumni = $alumniGateway->getByID($alumniAlumnusID);

        if (empty($alumni)) {
            //Fail 2
            $URL .= '&return=error2';
            header("Location: {$URL}");
        } else {
            //Proceed!
            $title = $_POST['title'] ?? '';
            $surname = $_POST['surname'] ?? '';
            $firstName = $_POST['firstName'] ?? '';
            $officialName = $_POST['officialName'] ?? '';
            $maidenName = $_POST['maidenName'] ?? '';
            $gender = $_POST['gender'] ?? '';
            $username = $_POST['username'] ?? '';
            $dob = $_POST['dob'] ? Format::dateConvert($_POST['dob']) : '';
            $email = $_POST['email'] ?? '';
            $address1Country = $_POST['address1Country'] ?? '';
            $profession = $_POST['profession'] ?? '';
            $employer = $_POST['employer'] ?? '';
            $jobTitle = $_POST['jobTitle'] ?? '';
            $graduatingYear = $_POST['graduatingYear'] ?? '';
            $formerRole = $_POST['formerRole'] ?? '';
            $gibbonPersonID = $_POST['gibbonPersonID'] ?? '';

            if (empty($surname) or empty($firstName) or empty($gender) or empty($email) or empty($formerRole)) {
                //Fail 3
                $URL .= '&return=error3';
                header("Location: {$URL}");
            } else {
                //Write to database
                $fields = ['title' => $title, 'surname' => $surname, 'firstName' => $firstName, 'officialName' => $officialName, 'maidenName' => $maidenName, 'gender' => $gender, 'username' => $username, 'dob' => $dob, 'email' => $email, 'address1Country' => $address1Country, 'profession' => $profession, 'employer' => $employer, 'jobTitle' => $jobTitle, 'graduatingYear' => $graduatingYear, 'formerRole' => $formerRole, 'gibbonPersonID' => $gibbonPersonID];
                $dataAlumni = array_filter($fields, function($field) { return !empty($field[0]); });
                
                $alumniGateway->update($alumni['alumniAlumnusID'], $dataAlumni);

                //Success 0
                $URL .= '&return=success0';
                header("Location: {$URL}");
            }
        }
    }
}
