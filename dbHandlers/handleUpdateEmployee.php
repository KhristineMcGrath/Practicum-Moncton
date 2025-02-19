<?php
include("connect.php");

// Check if the data exists
function getEmployeeById($empId) {
    global $con;
    $sql = "SELECT `Emp_ID`, `FirstName`, `LastName`, `Username`, `Email`, `Role`
            FROM `employee`
            WHERE `Emp_ID` = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $empId);  // changed $userId to $empId
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        return $result->fetch_assoc(); // Return the employee data
    }
    return false; // Employee not found
}

// Check if the username already exists (ignoring the current employee)
function isUserNameExist($userName, $excludeEmpId = null) {
    global $con;
    $sql = "SELECT 1 FROM `employee` WHERE `Username` = ?";
    
    if ($excludeEmpId) {
        $sql .= " AND `Emp_ID` != ?";  // changed `ID` to `Emp_ID`
    }

    $stmt = $con->prepare($sql);
    if ($excludeEmpId) {
        $stmt->bind_param("si", $userName, $excludeEmpId);  // changed $excludeUserId to $excludeEmpId
    } else {
        $stmt->bind_param("s", $userName);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0;
}

// Check if the email already exists (ignoring the current employee)
function isEmailExist($email, $excludeEmpId = null) {
    global $con;
    $sql = "SELECT 1 FROM `employee` WHERE `Email` = ?";

    if ($excludeEmpId) {
        $sql .= " AND `Emp_ID` != ?";  // changed `ID` to `Emp_ID`
    }

    $stmt = $con->prepare($sql);
    if ($excludeEmpId) {
        $stmt->bind_param("si", $email, $excludeEmpId);  // changed $excludeUserId to $excludeEmpId
    } else {
        $stmt->bind_param("s", $email);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

// Update employee details
function updateEmployee($empId, $firstName, $lastName, $userName, $email, $role) {
    global $con;
    $sql = "UPDATE `employee` SET 
            `FirstName` = ?, 
            `LastName` = ?, 
            `Username` = ?, 
            `Email` = ?, 
            `Role` = ?
            WHERE `Emp_ID` = ?";  // changed `ID` to `Emp_ID`
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssi", $firstName, $lastName, $userName, $email, $role, $empId);  // changed $userId to $empId
    
    return $stmt->execute();
}
?>
