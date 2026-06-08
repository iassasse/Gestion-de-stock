<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Material;
use App\Models\Espace;
use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'chef@example.com'],
            [
                'name' => 'Chef Magasinier',
                'role' => 'Chef Magasinier',
                'password' => Hash::make('Admin123!'),
                'is_active' => true,
                'is_super_chef_magasinier' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'magasinier@example.com'],
            [
                'name' => 'Magasinier',
                'role' => 'Magasinier',
                'password' => Hash::make('Admin123!'),
                'is_active' => true,
            ]
        );

        $espaces = [];
        $espaceTitles = ['Pole DIA', 'Pole GC', 'Magasin'];
        foreach ($espaceTitles as $title) {
            $espaces[$title] = Espace::firstOrCreate(['title' => $title]);
        }

        $catElectric = Category::firstOrCreate(['title' => 'Electric']);
        $catMechanical = Category::firstOrCreate(['title' => 'Mechanical']);
        $catInstrumentation = Category::firstOrCreate(['title' => 'Instrumentation']);
        $catSafety = Category::firstOrCreate(['title' => 'Safety']);

        $matCable = Material::firstOrCreate(
            ['ref' => 'MAT-ELEC-001'],
            [
                'name' => 'Copper Cable 2.5mm',
                'category_id' => $catElectric->id,
            ]
        );

        $matBolt = Material::firstOrCreate(
            ['ref' => 'MAT-MECH-002'],
            [
                'name' => 'Steel Bolt M12',
                'category_id' => $catMechanical->id,
            ]
        );

        $matGauge = Material::firstOrCreate(
            ['ref' => 'MAT-INST-003'],
            [
                'name' => 'Digital Pressure Gauge',
                'category_id' => $catInstrumentation->id,
            ]
        );

        $matHelmet = Material::firstOrCreate(
            ['ref' => 'MAT-SAFE-004'],
            [
                'name' => 'Safety Helmet Red',
                'category_id' => $catSafety->id,
            ]
        );

        Article::firstOrCreate(
            ['li_ref' => 'ART-CAB-001'],
            [
                'material_id' => $matCable->id,
                'espace_id' => $espaces['Pole DIA']->id,
            ]
        );

        Article::firstOrCreate(
            ['li_ref' => 'ART-CAB-002'],
            [
                'material_id' => $matCable->id,
                'espace_id' => $espaces['Magasin']->id,
            ]
        );

        Article::firstOrCreate(
            ['li_ref' => 'ART-BLT-001'],
            [
                'material_id' => $matBolt->id,
                'espace_id' => $espaces['Pole GC']->id,
            ]
        );

        Article::firstOrCreate(
            ['li_ref' => 'ART-GAU-001'],
            [
                'material_id' => $matGauge->id,
                'espace_id' => $espaces['Pole DIA']->id,
            ]
        );

        Article::firstOrCreate(
            ['li_ref' => 'ART-HLM-001'],
            [
                'material_id' => $matHelmet->id,
                'espace_id' => $espaces['Magasin']->id,
            ]
        );
    }
}
