<?php

// Script for Citizenpedia population
// ==================================
// Just a simple cli php script for populate citizenpedia

// Config
$questionsJsonFile = "population.json";
$usersFile = "users.json";
$categoriesFile = "categories.json";
$tagsFile = "tags.json";


switch ($argv[1])
{
    case "tags" :
        echo "tags\n";
        getTags($questionsJsonFile);
        break;
    case "users" :
        echo "users\n";
        getUsers($usersFile);
        break;
    case "categories" :
        echo "categories\n";
        getCategories($categoriesFile);
        break;
    case "questions" :
        echo "questions";
        getQuestions($questionsJsonFile, $tagsFile, $usersFile, $categoriesFile);
        break;
    default :
        echo "Pass users, categories or questions\n";
    
}

// QUESTIONS \\
// ========= \\
function getQuestions($questionsJsonFile, $tagsFile, $usersFile, $categoriesFile)
{
    $questions = readJson($questionsJsonFile);
    $categories = readJson($categoriesFile);
    $tags = readJson($tagsFile);
    $users = readJson($usersFile);

    foreach ($questions as $question)
    {
        // db.questions.insert({"searchText" : "Support   Young   Carer", "title": "Support Young Carer","stars" : [ ],"comments" : [ ],"createdAt" : ISODate("2017-05-09T11:52:39.918Z"), "content": "How should I support a young carer", "category" : ObjectId("59118861613f4a34285ad0ec"), "user" : ObjectId("59118a59613f4a34285ad0f0"),"tags" : [	{"text": "Social","_id":  ObjectId("59118ef8613f4a34285ad0f2")}, {"text": "Young Carer","_id":  ObjectId("59118f11613f4a34285ad0f3")}],"answers" : [{"createdAt" : ISODate("2017-05-09T09:56:00.770Z"),	"comments" : [ ],"stars" : [ ],"user" : ObjectId("59118a52613f4a34285ad0ef"),"content" : "Caring can have significant impacts on a young person’s health, education and social development. Early identification, intervention and prevention are vital to reduce negative impacts on young carers and improve outcomes for them and their families.\\n\\nWhen a young carer has been identified, you should offer them a Young Carers Assessment, which is an easy-to-use, young-person friendly booklet providing you with a guided conversation to help young people talk about their home life and their caring and agree actions that will help."}]})
        
        $searchText = str_replace(' ','  ',$question['Content']);
        //$category = array_search($question['Category'],$categories);
        $category = $categories[array_search($question['Category'], array_column($categories, 'name'))]['_id'];



        $cadena = 'db.questions.insert({"searchText" : "'.$searchText.'",';
        $cadena += '"title": "'.$question['Title'].'",';
        $cadena += '"stars" : [ ],"comments" : [ ],"createdAt" : ISODate("2017-05-09T11:52:39.918Z"), ';
        $cadena += '"content": "'.$question['Content'].'",';
        $cadena += '"category" : ObjectId("'.$category.'"),';
        $cadena += '"user" : ObjectId("'.$users[array_rand($users)].'"),';
        $cadena += '"tags" : [	{"text": "'.$question['Tag1'].'", "_id":  ObjectId("59118ef8613f4a34285ad0f2")},';
        $cadena += '{"text": "Young Carer","_id":  ObjectId("59118f11613f4a34285ad0f3")}],';
        $cadena += '"answers" : [{"createdAt" : ISODate("2017-05-09T09:56:00.770Z"),';
        $cadena += '"comments" : [ ],"stars" : [ ],';
        $cadena += '"user" : ObjectId("59118a52613f4a34285ad0ef"),';
        $cadena += '"content" : "Caring can have significant impacts on a young person’s health, education and social development. Early iden';
        $cadena += '"}]})';

        //echo $cadena . "\n";
           	
    }

}


// CATEGORIES \\
// ========== \\
function getCategories($categoriesFile)
{
    $categories = readJson($categoriesFile);

    foreach ($categories as $category)
    {
        echo 'db.categories.insert({"name" : "'.$category['name'].'"})'."\n";
    }
}

// TAGS \\
// ===== \\
function getTags($questionsJsonFile)
{
    
    $questions = readJson($questionsJsonFile);

    $tags = array();

    foreach ($questions as $question)
    {
        if ($question["Tag1"])
            //echo $question["Tag1"] . "\n";
            if (!in_array($question["Tag1"], $tags))
                array_push($tags,$question["Tag1"]);
        if ($question["Tag2"])
            //echo $question["Tag2"] . "\n";
            if (!in_array($question["Tag2"], $tags))
                array_push($tags,$question["Tag2"]);
        if ($question["Tag3"])
            //echo $question["Tag3"] . "\n";
            if (!in_array($question["Tag3"], $tags))
                array_push($tags,$question["Tag3"]);
    } //foreach

    foreach ($tags as $tag)
    {
        echo 'db.tags.insert({"name" : "'.$tag.'"})'."\n";
    }

}

// USERS \\
//=======\\
function getUsers($usersFile)
{
    $users = readJson($usersFile);

    foreach ($users as $user)
    {
        echo 'db.users.insert({"name" : "'.$user['name'].'", "provider" : "local", "email" : "'.$user['email'].'", "password" : "test","role" : "user"})'."\n";
    }
}



// MAIN
// $users = readJson($usersFile);
// $categories = readJson($categoriesFile);


// Read JSON and Returns Array
function readJson($file)
{
    $string = file_get_contents($file);
    $jsonDecoded = json_decode($string, true);

    return $jsonDecoded;
}







// SAMPLES
//db.users.insert({"name" : "Jack Stoy", "provider" : "local", "email" : "test@test.com", "password" : "test","role" : "user"})
//db.users.insert({"name" : "John Doe", "provider" : "local", "email" : "johndoe@test.com", "password" : "test","role" : "user"})
// db.categories.insert({"name" : "School"})
// db.categories.insert({"name" : "Community"})
// db.categories.insert({"name" : "Social Service"})
//db.tags.insert({"name" : "Young Carer"})
//db.tags.insert({"name" : "Social"})


?>



