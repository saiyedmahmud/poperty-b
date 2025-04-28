<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contact = new Contact();
        $contact->contactOwnerId = 1;
        $contact->contactSourceId = 1;
        $contact->contactStageId = 1;
        $contact->firstName = 'John';
        $contact->lastName = 'Doe';
        $contact->dateOfBirth = '1990-01-01';
        $contact->companyId = 1;
        $contact->jobTitle = 'Software Engineer';
        $contact->department = 'IT';
        $contact->industryId = 1;
        $contact->email = 'example@gmail.com';
        $contact->phone = '1234567890';
        $contact->twitter = 'https://twitter.com/example';
        $contact->linkedin = 'https://www.linkedin.com/in/example';
        $contact->presentAddress = '123, Example Street';
        $contact->presentCity = 'Example City';
        $contact->presentZipCode = '123456';
        $contact->presentState = 'Example State';
        $contact->presentCountry = 'Example Country';
        $contact->permanentAddress = '123, Example Street';
        $contact->permanentCity = 'Example City';
        $contact->permanentZipCode = '123456';
        $contact->permanentState = 'Example State';
        $contact->permanentCountry = 'Example Country';
        $contact->description = 'Example Description';

        $contact->save();
    }
}
