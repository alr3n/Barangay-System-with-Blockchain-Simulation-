<?php

namespace Database\Seeders;

use App\Models\Resident;
use Illuminate\Database\Seeder;

class ResidentSeeder extends Seeder
{
    public function run(): void
    {
        $residents = [
            ['resident_code'=>'RES-2024-0001','first_name'=>'Jose','middle_name'=>'Reyes','last_name'=>'Santos','birthdate'=>'1980-03-15','gender'=>'male','civil_status'=>'married','address'=>'123 Rizal Street','contact_number'=>'09171234567','occupation'=>'Farmer','household_id'=>1,'is_household_head'=>true,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0002','first_name'=>'Maria','middle_name'=>'Cruz','last_name'=>'Santos','birthdate'=>'1983-07-22','gender'=>'female','civil_status'=>'married','address'=>'123 Rizal Street','contact_number'=>'09181234567','occupation'=>'Housewife','household_id'=>1,'is_household_head'=>false,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0003','first_name'=>'Carlo','middle_name'=>'Reyes','last_name'=>'Santos','birthdate'=>'2005-01-10','gender'=>'male','civil_status'=>'single','address'=>'123 Rizal Street','contact_number'=>null,'occupation'=>'Student','household_id'=>1,'is_household_head'=>false,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0004','first_name'=>'Ana','middle_name'=>'Bautista','last_name'=>'Dela Cruz','birthdate'=>'1975-11-05','gender'=>'female','civil_status'=>'married','address'=>'45 Mabini Avenue','contact_number'=>'09191234567','occupation'=>'Teacher','household_id'=>2,'is_household_head'=>true,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0005','first_name'=>'Roberto','middle_name'=>'Lim','last_name'=>'Dela Cruz','birthdate'=>'1972-09-18','gender'=>'male','civil_status'=>'married','address'=>'45 Mabini Avenue','contact_number'=>'09201234567','occupation'=>'Driver','household_id'=>2,'is_household_head'=>false,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0006','first_name'=>'Liza','middle_name'=>'Garcia','last_name'=>'Reyes','birthdate'=>'1990-04-30','gender'=>'female','civil_status'=>'single','address'=>'78 Luna Street','contact_number'=>'09211234567','occupation'=>'Nurse','household_id'=>3,'is_household_head'=>true,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0007','first_name'=>'Pedro','middle_name'=>'Torres','last_name'=>'Garcia','birthdate'=>'1965-12-25','gender'=>'male','civil_status'=>'widowed','address'=>'12 Bonifacio Road','contact_number'=>'09221234567','occupation'=>'Retired','household_id'=>4,'is_household_head'=>true,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0008','first_name'=>'Elena','middle_name'=>'Villanueva','last_name'=>'Garcia','birthdate'=>'1998-08-14','gender'=>'female','civil_status'=>'single','address'=>'12 Bonifacio Road','contact_number'=>'09231234567','occupation'=>'Call Center Agent','household_id'=>4,'is_household_head'=>false,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0009','first_name'=>'Marcos','middle_name'=>'Aquino','last_name'=>'Flores','birthdate'=>'1955-06-12','gender'=>'male','civil_status'=>'married','address'=>'90 Aguinaldo Street','contact_number'=>'09241234567','occupation'=>'Carpenter','household_id'=>5,'is_household_head'=>true,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0010','first_name'=>'Rosario','middle_name'=>'Mendoza','last_name'=>'Flores','birthdate'=>'1958-02-28','gender'=>'female','civil_status'=>'married','address'=>'90 Aguinaldo Street','contact_number'=>null,'occupation'=>'Vendor','household_id'=>5,'is_household_head'=>false,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0011','first_name'=>'Dennis','middle_name'=>'Pascual','last_name'=>'Ramos','birthdate'=>'1988-10-03','gender'=>'male','civil_status'=>'married','address'=>'34 Quezon Boulevard','contact_number'=>'09251234567','occupation'=>'Security Guard','household_id'=>6,'is_household_head'=>true,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0012','first_name'=>'Cynthia','middle_name'=>'Ocampo','last_name'=>'Ramos','birthdate'=>'1991-05-17','gender'=>'female','civil_status'=>'married','address'=>'34 Quezon Boulevard','contact_number'=>'09261234567','occupation'=>'Dressmaker','household_id'=>6,'is_household_head'=>false,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0013','first_name'=>'Anthony','middle_name'=>'Castillo','last_name'=>'Morales','birthdate'=>'1996-07-08','gender'=>'male','civil_status'=>'single','address'=>'56 Magsaysay Lane','contact_number'=>'09271234567','occupation'=>'Electrician','household_id'=>7,'is_household_head'=>true,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0014','first_name'=>'Jenny','middle_name'=>'Navarro','last_name'=>'Lopez','birthdate'=>'2000-03-22','gender'=>'female','civil_status'=>'single','address'=>'22 Marcos Street','contact_number'=>'09281234567','occupation'=>'Student','household_id'=>8,'is_household_head'=>false,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0015','first_name'=>'Fernando','middle_name'=>'Herrera','last_name'=>'Lopez','birthdate'=>'1969-09-01','gender'=>'male','civil_status'=>'separated','address'=>'22 Marcos Street','contact_number'=>'09291234567','occupation'=>'Mechanic','household_id'=>8,'is_household_head'=>true,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0016','first_name'=>'Maricel','middle_name'=>'Buenaventura','last_name'=>'Delos Santos','birthdate'=>'1985-12-12','gender'=>'female','civil_status'=>'married','address'=>'67 Gen. Luna Street','contact_number'=>'09301234567','occupation'=>'Businesswoman','household_id'=>null,'is_household_head'=>false,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0017','first_name'=>'Ramon','middle_name'=>'Infante','last_name'=>'Espiritu','birthdate'=>'1945-04-18','gender'=>'male','civil_status'=>'widowed','address'=>'89 Padre Burgos St.','contact_number'=>null,'occupation'=>'Retired','household_id'=>null,'is_household_head'=>false,'resident_status'=>'active'],
            ['resident_code'=>'RES-2024-0018','first_name'=>'Gloria','middle_name'=>'Salazar','last_name'=>'Magno','birthdate'=>'1978-08-20','gender'=>'female','civil_status'=>'married','address'=>'11 Mactan Road','contact_number'=>'09311234567','occupation'=>'Laundrywoman','household_id'=>null,'is_household_head'=>false,'resident_status'=>'inactive'],
        ];

        foreach ($residents as $r) {
            Resident::create($r);
        }
    }
}
