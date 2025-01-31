<?php
include("connect.php");

// Check if the data exists
function getEmployeeById($userId) {
    global $con;
    $sql = "SELECT `ID`, `FirstName`, `LastName`, `Username`, `Email`, `Role`
            FROM `employee`
            WHERE `ID` = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        return $result->fetch_assoc(); // Return the employee data
    }
    return false; // Employee not found
}

// Check if the username already exists (ignoring the current employee)
function isUserNameExist($userName, $excludeUserId = null) {
    global $con;
    $sql = "SELECT 1 FROM `employee` WHERE `Username` = ?";
    
    if ($excludeUserId) {
        $sql .= " AND `ID` != ?";
    }

    $stmt = $con->prepare($sql);
    if ($excludeUserId) {
        $stmt->bind_param("si", $userName, $excludeUserId);
    } else {
        $stmt->bind_param("s", $userName);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0;
}

// Check if the email already exists (ignoring the current employee)
function isEmailExist($email, $excludeUserId = null) {
    global $con;
    $sql = "SELECT 1 FROM `employee` WHERE `Email` = ?";

    if ($excludeUserId) {
        $sql .= " AND `ID` != ?";
    }

    $stmt = $con->prepare($sql);
    if ($excludeUserId) {
        $stmt->bind_param("si", $email, $excludeUserId);
    } else {
        $stmt->bind_param("s", $email);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

// Update employee details
function updateEmployee($userId, $firstName, $lastName, $userName, $email, $role) {
    global $con;
    $sql = "UPDATE `employee` SET 
            `FirstName` = ?, 
            `LastName` = ?, 
            `Username` = ?, 
            `Email` = ?, 
            `Role` = ?
            WHERE `ID` = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssi", $firstName, $lastName, $userName, $email, $role, $userId);
    
    return $stmt->execute();
}
?>
