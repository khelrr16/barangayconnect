<?php

namespace Database\Seeders;

use App\Models\Health\Medicine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = [
            [
                'name' => 'BCG',
                'description' => 'Bacillus Calmette-Guérin (BCG) vaccine is a live attenuated vaccine primarily used to prevent tuberculosis (TB), especially severe childhood forms like TB meningitis, by boosting immunity against Mycobacterium bovis',
                'type' => 'Vaccine',
                'dose_volume' => '0.05 ml',
            ], [
                'name' => 'Hepa B-BD',
                'description' => 'Hepatitis B birth dose (HepB-BD) is a crucial, safe, and highly effective vaccine administered within 24 hours of birth to prevent vertical transmission of the hepatitis B virus (HBV) from mother to child',
                'type' => 'Vaccine',
                'dose_volume' => '0.5 ml',
            ], [
                'name' => 'DPT-HiB-HepB',
                'description' => 'The DTP-HepB-Hib (pentavalent) vaccine is a 5-in-1 injection protecting against diphtheria, tetanus, pertussis (whooping cough), hepatitis B, and Haemophilus influenzae type b (Hib)',
                'type' => 'Vaccine',
                'dose_volume' => '0.5 ml',
            ], [
                'name' => 'OPV',
                'description' => 'The Oral Polio Vaccine (OPV) is a highly effective, live-attenuated vaccine administered via oral drops to prevent poliomyelitis, reducing global cases by >99%',
                'type' => 'Vaccine',
                'dose_volume' => '0.1 ml',

            ], [
                'name' => 'PCV',
                'description' => 'The Pneumococcal Conjugate Vaccine (PCV) protects against serious infections caused by Streptococcus pneumoniae bacteria, such as pneumonia, meningitis, and bloodstream infections',
                'type' => 'Vaccine',
                'dose_volume' => '0.5 ml',
            ], [
                'name' => 'IPV',
                'description' => 'The Inactivated Poliovirus Vaccine (IPV) is a safe, highly effective injection that protects against all three types of poliovirus by inducing immunity without causing disease',
                'type' => 'Vaccine',
                'dose_volume' => '0.5 ml',
            ], [
                'name' => 'Vitamin A',
                'description' => 'Vitamin A is a fat-soluble nutrient essential for vision, immune function, reproduction, and cellular communication.',
                'type' => 'Vitamin',
                'dose_volume' => '100,000 IU',
            ], [
                'name' => 'MNP',
                'description' => 'Microneedle patches (MNPs) are an innovative, needle-free vaccine delivery system using microscopic needles on a patch to deliver vaccines intradermally',
                'type' => 'Vitamin',
                'dose_volume' => '1 g',
            ], [
                'name' => 'MMR',
                'description' => 'The MMR vaccine is a highly effective, two-dose, live-attenuated vaccine that protects against measles, mumps, and rubella',
                'type' => 'Vaccine',
                'dose_volume' => '0.5 ml',
            ], [
                'name' => 'IRON',
                'description' => 'Iron supplements are essential for preventing anemia and supporting healthy growth and development in infants and young children',
                'type' => 'Supplement',
                'dose_volume' => '100 mg',
            ]
        ];

        $batches = [
            [
                    'batch_number' => 'BCG-2026-001',
                    'manufacturer' => 'Global Vaccine Co.',
                    'quantity_received' => 100,
                    'quantity_remaining' => 100,
                    'received_date' => '2026-01-15',
                    'expiry_date' => '2026-12-31',
            ],  [
                    'batch_number' => 'HepB-BD-2026-001',
                    'manufacturer' => 'Global Vaccine Co.',
                    'quantity_received' => 100,
                    'quantity_remaining' => 100,
                    'received_date' => '2026-01-15',
                    'expiry_date' => '2026-12-31',
            ], [
                    'batch_number' => 'DPT-HiB-HepB-2026-001',
                    'manufacturer' => 'Global Vaccine Co.',
                    'quantity_received' => 100,
                    'quantity_remaining' => 100,
                    'received_date' => '2026-01-15',
                    'expiry_date' => '2026-12-31',
            ], [
                    'batch_number' => 'OPV-2026-001',
                    'manufacturer' => 'Global Vaccine Co.',
                    'quantity_received' => 100,
                    'quantity_remaining' => 100,
                    'received_date' => '2026-01-15',
                    'expiry_date' => '2026-12-31',
            ], [
                    'batch_number' => 'PCV-2026-001',
                    'manufacturer' => 'Global Vaccine Co.',
                    'quantity_received' => 100,
                    'quantity_remaining' => 100,
                    'received_date' => '2026-01-15',
                    'expiry_date' => '2026-12-31',
            ], [
                    'batch_number' => 'IPV-2026-001',
                    'manufacturer' => 'Global Vaccine Co.',
                    'quantity_received' => 100,
                    'quantity_remaining' => 100,
                    'received_date' => '2026-01-15',
                    'expiry_date' => '2026-12-31',
            ], [
                    'batch_number' => 'VIT-A-2026-001',
                    'manufacturer' => 'Global Vitamin Co.',
                    'quantity_received' => 100,
                    'quantity_remaining' => 100,
                    'received_date' => '2026-01-15',
                    'expiry_date' => '2026-12-31',
            ], [
                'batch_number' => 'MNP-2026-001',
                'manufacturer' => 'Global Vaccine Co.',
                'quantity_received' => 100,
                'quantity_remaining' => 100,
                'received_date' => '2026-01-15',
                'expiry_date' => '2026-12-31',
            ], [
                'batch_number' => 'MMR-2026-001',
                'manufacturer' => 'Global Vaccine Co.',
                'quantity_received' => 100,
                'quantity_remaining' => 100,
                'received_date' => '2026-01-15',
                'expiry_date' => '2026-12-31',
            ], [
                'batch_number' => 'IRON-2026-001',
                'manufacturer' => 'Global Supplement Co.',
                'quantity_received' => 100,
                'quantity_remaining' => 100,
                'received_date' => '2026-01-15',
                'expiry_date' => '2026-12-31',
            ]
        ];

        foreach($medicines as $index => $medicine){
            Medicine::create($medicine)->batches()->create($batches[$index]);
        }
    }
}
