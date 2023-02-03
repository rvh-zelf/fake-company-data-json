<?php
/*
Simple PHP Script to generate random company data
Generates an array that is then converted to json for output

Dependant on the Faker PHP library by Francois Zaninotto v1.9.2 (from 11 December 2020)
Github repository: https://github.com/fzaninotto/Faker

*/

require_once 'Faker-master/src/autoload.php';

//Function to generate slugs for domain names and email addresses
function createSlug($str, $delimiter = '-'){
    $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
    return $slug;
}

$tel = 0;
$limit = 512;

while ($tel < $limit) {

  $faker = Faker\Factory::create();


  $companies[$tel]["name"] = $faker->company;
  $companies[$tel]["phone_number"] = $faker->phoneNumber;

  $domain_name = createSlug($companies[$tel]["name"],'').'.'.$faker->tld;

  $companies[$tel]["email"] = $faker->userName.'@'.$domain_name;
  $companies[$tel]["website"] = 'www.'.$domain_name;
  $companies[$tel]["about"] = $faker->catchPhrase . ' ' . $faker->bs . ' with ' . $faker->catchPhrase . ' and ' . $faker->bs.'.';
  $companies[$tel]["head_office"] = array(
    'street' => $faker->buildingNumber . ' ' .$faker->streetName,
    'suburb'  => $faker->lastName.$faker->citySuffix.' '.$faker->streetSuffix,
    'city'  => $faker->city,
    'state'  => $faker->state,
    'country'  => $faker->country,
    'latitude'  => $faker->latitude,
    'longitude'  => $faker->longitude
  );
  $companies[$tel]["founded"] = $faker->dayOfMonth.' '.$faker->monthName.' '.$faker->year;
  $companies[$tel]["founder"] = $faker->firstName.' '.$faker->lastName;

  //Key Personnel
  $random = rand(0,5);
  if( $random > 0 ) {
    $tel_random = 0;

    while($tel_random < $random){
      $contact_name = $faker->firstName.' '.$faker->lastName;

      $companies[$tel]["key_personnel"][$tel_random] = array(
        'contact' => $contact_name,
        'email' => createSlug($contact_name,'.').'@'.$domain_name,
        'tel' => $faker->e164PhoneNumber
      );
      $tel_random++;
    }

  }


  //Social Media
  $random = rand(0,10);
  //Twitter
  if ($random > 5) {
    $companies[$tel]["social_media"]['twitter'] = '@'.createSlug($companies[$tel]["name"],'');
  }
  //Instagram
  $random = rand(0,10);
  if ($random < 5) {
    $companies[$tel]["social_media"]['instagram'] = '@'.createSlug($companies[$tel]["name"],'');
    if( $random == 0 ) {
      $companies[$tel]["social_media"]['instagram'].= $faker->stateAbbr;
    }
  }
  //Facebook
  $random = rand(0,10);
  if ($random > 5) {
    $companies[$tel]["social_media"]['facebook'] = 'https://www.facebook.com/'.createSlug($companies[$tel]["name"],'');
  }
  //LinkedIn
  $random = rand(0,10);
  if ($random < 5) {
    $companies[$tel]["social_media"]['linkedin'] = 'https://www.linkedin.com/company/'.createSlug($companies[$tel]["name"],'').'/';
  }
  //YouTube
  /*
  URL based on random YouTube channel: https://www.youtube.com/channel/UCNULU4Apmi_9rfQlSZ0ueQQ
  Code looks messy. Might cleanup  later
  */
  $random = rand(0,10);
  if ($random > 5) {
    $first = $faker->randomLetter.$faker->randomLetter.$faker->randomLetter.$faker->randomLetter.$faker->randomLetter.$faker->randomLetter;
    $second = strtoupper($faker->randomLetter).$faker->randomLetter.$faker->randomLetter.$faker->randomLetter;
    $third = $faker->randomLetter.$faker->randomLetter.strtoupper($faker->randomLetter).$faker->randomLetter.strtoupper($faker->randomLetter).strtoupper($faker->randomLetter).strtoupper($faker->randomLetter).$faker->randomLetter.$faker->randomLetter.strtoupper($faker->randomLetter).strtoupper($faker->randomLetter);
    $companies[$tel]["social_media"]['youtube'] = 'https://www.youtube.com/channel/'.strtoupper($first).rand(0,9).$second.'_'.rand(0,9).$third;
  }



  $tel++;
}

echo json_encode($companies);
//print_r($companies);

 ?>
