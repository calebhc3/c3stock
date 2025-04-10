<?php

namespace Database\Seeders;

use App\Models\Insumo;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsumoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $insumosComMedida = [
        'Algodão bolinha com 100 un' => 'unidade',
        'Álcool 70% (100ml)' => 'ml',
        'Gaze 13 fios 7,5cm x 7,5cm (c 100)' => 'unidade',
        'Luva de procedimento cirúrgico' => 'par',
        'Máscara Cirúrgica (cx 50)' => 'unidade',
        'Clorexidina Degermante 2% (100ml)' => 'ml',
        'Iodopovidona 10%' => 'ml',
        'Soro Fisiológico 0,9%  ampola' => 'unidade',
        'Micropore' => '%',
        'Esparadrapo' => '%',
        'Curativo transparente (Band-Aid) c 10' => 'unidade',
        'Atadura crepom 10cm X 1,8m' => 'unidade',
        'Atadura crepom 15cm X 1,8m' => 'unidade',
        'Atadura crepom 20cm X 1,8m' => 'unidade',
        'Lençol papel hospitalar Maca' => 'unidade',
        'Descarpack 13L' => 'unidade',
        'Benalet Pastilha c 12' => 'unidade',
        'Bilastina c 10' => 'unidade',
        'Buscofem com 20' => 'unidade',
        'Buscopan composto c 20' => 'unidade',
        'Cetoprofeno comp com 20' => 'unidade',
        'Cimegripe com 20' => 'unidade',
        'Decongex com 12' => 'unidade',
        'Diclofenaco Spray' => '%',
        'Dipirona (com 20)' => 'unidade',
        'Diprogenta Pomada (30g)' => 'mg',
        'Epocler' => 'unidade',
        'Floratil (Enterogermina Plus)' => 'unidade',
        'Ibuprofeno com 600 (c 20)' => 'unidade',
        'Nebacetin Pomada 50 g' => '%',
        'Nimesulida comp c 12' => 'unidade',
        'Omeprazol Comprimido com 28' => 'unidade',
        'Paracetamol comp c 20' => 'unidade',
        'Prednisolona Comprimido c 10' => 'unidade',
        'Torsilax c 10' => 'unidade',
        'Trok-N 30 g' => 'mg',
        'Vonau 4mg (30cp)' => 'unidade',
        // Versões sem descrição adicional (para outras unidades)
        'Algodão bolinha' => 'unidade',
        'Gaze 13 fios 7,5cm x 7,5cm' => 'unidade',
        'Máscara Cirúrgica' => 'unidade',
        'Curativo transparente (Band-Aid)' => 'unidade',
        'Benalet Pastilha' => 'unidade',
        'Bilastina' => 'unidade',
        'Buscofem' => 'unidade',
        'Buscopan composto' => 'unidade',
        'Cetoprofeno comp' => 'unidade',
        'Cimegripe' => 'unidade',
        'Decongex' => 'unidade',
        'Dipirona' => 'unidade',
        'Diprogenta Pomada' => 'mg',
        'Floratil (Enterogermina Plus)' => 'unidade',
        'Ibuprofeno' => 'unidade',
        'Nebacetin Pomada' => '%',
        'Nimesulida' => 'unidade',
        'Omeprazol Comprimido' => 'unidade',
        'Paracetamol' => 'unidade',
        'Prednisolona Comprimido' => 'unidade',
        'Torsilax' => 'unidade',
        'Trok-N' => 'mg',
        'Vonau' => 'unidade',
    ];
    
    public function run(): void
    {
        // Dados do Excel estruturados
        $dados = [
            // Betim
            'Betim' => [
                ['Algodão bolinha com 100 un', 750],
                ['Álcool 70% (100ml)', 1500],
                ['Gaze 13 fios 7,5cm x 7,5cm (c 100)', 300],
                ['Luva de procedimento cirúrgico', 150],
                ['Máscara Cirúrgica (cx 50)', 300],
                ['Clorexidina Degermante 2% (100ml)', 2250],
                ['Iodopovidona 10%', 1500],
                ['Soro Fisiológico 0,9%  ampola', 300],
                ['Micropore', 1500],
                ['Esparadrapo', 1500],
                ['Curativo transparente (Band-Aid) c 10', 300],
                ['Atadura crepom 10cm X 1,8m', 150],
                ['Atadura crepom 15cm X 1,8m', 150],
                ['Atadura crepom 20cm X 1,8m', 150],
                ['Lençol papel hospitalar Maca', 150],
                ['Descarpack 13L', 15],
                ['Benalet Pastilha c 12', 75],
                ['Bilastina c 10', 75],
                ['Buscofem com 20', 75],
                ['Buscopan composto c 20', 75],
                ['Cetoprofeno comp com 20', 75],
                ['Cimegripe com 20', 75],
                ['Decongex com 12', 75],
                ['Diclofenaco Spray', 75],
                ['Dipirona (com 20)', 75],
                ['Diprogenta Pomada (30g)', 75],
                ['Epocler', 75],
                ['Floratil (Enterogermina Plus)', 75],
                ['Ibuprofeno com 600 (c 20)', 75],
                ['Nebacetin Pomada 50 g', 75],
                ['Nimesulida comp c 12', 75],
                ['Omeprazol Comprimido com 28', 75],
                ['Paracetamol comp c 20', 75],
                ['Prednisolona Comprimido c 10', 75],
                ['Torsilax c 10', 75],
                ['Trok-N 30 g', 75],
                ['Vonau 4mg (30cp)', 75],
            ],
            // Guarulhos
            'Guarulhos' => [
                ['Algodão bolinha', 889],
                ['Álcool 70% (100ml)', 1778],
                ['Gaze 13 fios 7,5cm x 7,5cm', 356],
                ['Luva de procedimento cirúrgico', 178],
                ['Máscara Cirúrgica', 356],
                ['Clorexidina Degermante 2% (100ml)', 2666],
                ['Iodopovidona 10%', 1778],
                ['Soro Fisiológico 0,9%  ampola', 356],
                ['Micropore', 1778],
                ['Esparadrapo', 1778],
                ['Curativo transparente (Band-Aid)', 356],
                ['Atadura crepom 10cm X 1,8m', 178],
                ['Atadura crepom 15cm X 1,8m', 178],
                ['Atadura crepom 20cm X 1,8m', 178],
                ['Lençol papel hospitalar Maca', 178],
                ['Descarpack 13L', 18],
                ['Benalet Pastilha', 89],
                ['Bilastina', 89],
                ['Buscofem', 89],
                ['Buscopan composto', 89],
                ['Cetoprofeno comp', 89],
                ['Cimegripe', 89],
                ['Decongex', 89],
                ['Diclofenaco Spray', 89],
                ['Dipirona', 89],
                ['Diprogenta Pomada', 89],
                ['Epocler', 89],
                ['Floratil (Enterogermina Plus)', 89],
                ['Ibuprofeno', 89],
                ['Nebacetin Pomada', 89],
                ['Nimesulida', 89],
                ['Omeprazol Comprimido', 89],
                ['Paracetamol', 89],
                ['Prednisolona Comprimido', 89],
                ['Torsilax', 89],
                ['Trok-N', 89],
                ['Vonau', 89],
            ],
            // Santana de Parnaiba
            'Santana de Parnaiba' => [
                ['Algodão bolinha', 1309],
                ['Álcool 70% (100ml)', 2618],
                ['Gaze 13 fios 7,5cm x 7,5cm', 524],
                ['Luva de procedimento cirúrgico', 262],
                ['Máscara Cirúrgica', 524],
                ['Clorexidina Degermante 2% (100ml)', 3926],
                ['Iodopovidona 10%', 2618],
                ['Soro Fisiológico 0,9%  ampola', 524],
                ['Micropore', 2618],
                ['Esparadrapo', 2618],
                ['Curativo transparente (Band-Aid)', 524],
                ['Atadura crepom 10cm X 1,8m', 262],
                ['Atadura crepom 15cm X 1,8m', 262],
                ['Atadura crepom 20cm X 1,8m', 262],
                ['Lençol papel hospitalar Maca', 262],
                ['Descarpack 13L', 26],
                ['Benalet Pastilha', 131],
                ['Bilastina', 131],
                ['Buscofem', 131],
                ['Buscopan composto', 131],
                ['Cetoprofeno comp', 131],
                ['Cimegripe', 131],
                ['Decongex', 131],
                ['Diclofenaco Spray', 131],
                ['Dipirona', 131],
                ['Diprogenta Pomada', 131],
                ['Epocler', 131],
                ['Floratil (Enterogermina Plus)', 131],
                ['Ibuprofeno', 131],
                ['Nebacetin Pomada', 131],
                ['Nimesulida', 131],
                ['Omeprazol Comprimido', 131],
                ['Paracetamol', 131],
                ['Prednisolona Comprimido', 131],
                ['Torsilax', 131],
                ['Trok-N', 131],
                ['Vonau', 131],
            ],
            // Simões Filho
            'Simões Filho' => [
                ['Algodão bolinha', 459],
                ['Álcool 70% (100ml)', 918],
                ['Gaze 13 fios 7,5cm x 7,5cm', 184],
                ['Luva de procedimento cirúrgico', 92],
                ['Máscara Cirúrgica', 184],
                ['Clorexidina Degermante 2% (100ml)', 1376],
                ['Iodopovidona 10%', 918],
                ['Soro Fisiológico 0,9%  ampola', 184],
                ['Micropore', 918],
                ['Esparadrapo', 918],
                ['Curativo transparente (Band-Aid)', 184],
                ['Atadura crepom 10cm X 1,8m', 92],
                ['Atadura crepom 15cm X 1,8m', 92],
                ['Atadura crepom 20cm X 1,8m', 92],
                ['Lençol papel hospitalar Maca', 92],
                ['Descarpack 13L', 9],
                ['Benalet Pastilha', 46],
                ['Bilastina', 46],
                ['Buscofem', 46],
                ['Buscopan composto', 46],
                ['Cetoprofeno comp', 46],
                ['Cimegripe', 46],
                ['Decongex', 46],
                ['Diclofenaco Spray', 46],
                ['Dipirona', 46],
                ['Diprogenta Pomada', 46],
                ['Epocler', 46],
                ['Floratil (Enterogermina Plus)', 46],
                ['Ibuprofeno', 46],
                ['Nebacetin Pomada', 46],
                ['Nimesulida', 46],
                ['Omeprazol Comprimido', 46],
                ['Paracetamol', 46],
                ['Prednisolona Comprimido', 46],
                ['Torsilax', 46],
                ['Trok-N', 46],
                ['Vonau', 46],
            ],
            // Cravinhos
            'Cravinhos' => [
                ['Algodão bolinha', 546],
                ['Álcool 70% (100ml)', 1093],
                ['Gaze 13 fios 7,5cm x 7,5cm', 219],
                ['Luva de procedimento cirúrgico', 109],
                ['Máscara Cirúrgica', 219],
                ['Clorexidina Degermante 2% (100ml)', 1639],
                ['Iodopovidona 10%', 1093],
                ['Soro Fisiológico 0,9%  ampola', 219],
                ['Micropore', 1093],
                ['Esparadrapo', 1093],
                ['Curativo transparente (Band-Aid)', 219],
                ['Atadura crepom 10cm X 1,8m', 109],
                ['Atadura crepom 15cm X 1,8m', 109],
                ['Atadura crepom 20cm X 1,8m', 109],
                ['Lençol papel hospitalar Maca', 109],
                ['Descarpack 13L', 11],
                ['Benalet Pastilha', 55],
                ['Bilastina', 55],
                ['Buscofem', 55],
                ['Buscopan composto', 55],
                ['Cetoprofeno comp', 55],
                ['Cimegripe', 55],
                ['Decongex', 55],
                ['Diclofenaco Spray', 55],
                ['Dipirona', 55],
                ['Diprogenta Pomada', 55],
                ['Epocler', 55],
                ['Floratil (Enterogermina Plus)', 55],
                ['Ibuprofeno', 55],
                ['Nebacetin Pomada', 55],
                ['Nimesulida', 55],
                ['Omeprazol Comprimido', 55],
                ['Paracetamol', 55],
                ['Prednisolona Comprimido', 55],
                ['Torsilax', 55],
                ['Trok-N', 55],
                ['Vonau', 55],
            ],
            // Cabo de Sto Agostinho
            'Cabo de Sto Agostinho' => [
                ['Algodão bolinha', 404],
                ['Álcool 70% (100ml)', 808],
                ['Gaze 13 fios 7,5cm x 7,5cm', 162],
                ['Luva de procedimento cirúrgico', 81],
                ['Máscara Cirúrgica', 162],
                ['Clorexidina Degermante 2% (100ml)', 1211],
                ['Iodopovidona 10%', 808],
                ['Soro Fisiológico 0,9%  ampola', 162],
                ['Micropore', 808],
                ['Esparadrapo', 808],
                ['Curativo transparente (Band-Aid)', 162],
                ['Atadura crepom 10cm X 1,8m', 81],
                ['Atadura crepom 15cm X 1,8m', 81],
                ['Atadura crepom 20cm X 1,8m', 81],
                ['Lençol papel hospitalar Maca', 81],
                ['Descarpack 13L', 8],
                ['Benalet Pastilha', 40],
                ['Bilastina', 40],
                ['Buscofem', 40],
                ['Buscopan composto', 40],
                ['Cetoprofeno comp', 40],
                ['Cimegripe', 40],
                ['Decongex', 40],
                ['Diclofenaco Spray', 40],
                ['Dipirona', 40],
                ['Diprogenta Pomada', 40],
                ['Epocler', 40],
                ['Floratil (Enterogermina Plus)', 40],
                ['Ibuprofeno', 40],
                ['Nebacetin Pomada', 40],
                ['Nimesulida', 40],
                ['Omeprazol Comprimido', 40],
                ['Paracetamol', 40],
                ['Prednisolona Comprimido', 40],
                ['Torsilax', 40],
                ['Trok-N', 40],
                ['Vonau', 40],
            ],
            // Gravatai
            'Gravatai' => [
                ['Algodão bolinha', 256],
                ['Álcool 70% (100ml)', 513],
                ['Gaze 13 fios 7,5cm x 7,5cm', 103],
                ['Luva de procedimento cirúrgico', 51],
                ['Máscara Cirúrgica', 103],
                ['Clorexidina Degermante 2% (100ml)', 769],
                ['Iodopovidona 10%', 513],
                ['Soro Fisiológico 0,9%  ampola', 103],
                ['Micropore', 513],
                ['Esparadrapo', 513],
                ['Curativo transparente (Band-Aid)', 103],
                ['Atadura crepom 10cm X 1,8m', 51],
                ['Atadura crepom 15cm X 1,8m', 51],
                ['Atadura crepom 20cm X 1,8m', 51],
                ['Lençol papel hospitalar Maca', 51],
                ['Descarpack 13L', 5],
                ['Benalet Pastilha', 26],
                ['Bilastina', 26],
                ['Buscofem', 26],
                ['Buscopan composto', 26],
                ['Cetoprofeno comp', 26],
                ['Cimegripe', 26],
                ['Decongex', 26],
                ['Diclofenaco Spray', 26],
                ['Dipirona', 26],
                ['Diprogenta Pomada', 26],
                ['Epocler', 26],
                ['Floratil (Enterogermina Plus)', 26],
                ['Ibuprofeno', 26],
                ['Nebacetin Pomada', 26],
                ['Nimesulida', 26],
                ['Omeprazol Comprimido', 26],
                ['Paracetamol', 26],
                ['Prednisolona Comprimido', 26],
                ['Torsilax', 26],
                ['Trok-N', 26],
                ['Vonau', 26],
            ],
            // Louveiras
            'Louveiras' => [
                ['Algodão bolinha', 508],
                ['Álcool 70% (100ml)', 1015],
                ['Gaze 13 fios 7,5cm x 7,5cm', 203],
                ['Luva de procedimento cirúrgico', 102],
                ['Máscara Cirúrgica', 203],
                ['Clorexidina Degermante 2% (100ml)', 1523],
                ['Iodopovidona 10%', 1015],
                ['Soro Fisiológico 0,9%  ampola', 203],
                ['Micropore', 1015],
                ['Esparadrapo', 1015],
                ['Curativo transparente (Band-Aid)', 203],
                ['Atadura crepom 10cm X 1,8m', 102],
                ['Atadura crepom 15cm X 1,8m', 102],
                ['Atadura crepom 20cm X 1,8m', 102],
                ['Lençol papel hospitalar Maca', 102],
                ['Descarpack 13L', 10],
                ['Benalet Pastilha', 51],
                ['Bilastina', 51],
                ['Buscofem', 51],
                ['Buscopan composto', 51],
                ['Cetoprofeno comp', 51],
                ['Cimegripe', 51],
                ['Decongex', 51],
                ['Diclofenaco Spray', 51],
                ['Dipirona', 51],
                ['Diprogenta Pomada', 51],
                ['Epocler', 51],
                ['Floratil (Enterogermina Plus)', 51],
                ['Ibuprofeno', 51],
                ['Nebacetin Pomada', 51],
                ['Nimesulida', 51],
                ['Omeprazol Comprimido', 51],
                ['Paracetamol', 51],
                ['Prednisolona Comprimido', 51],
                ['Torsilax', 51],
                ['Trok-N', 51],
                ['Vonau', 51],
            ],
            // Hidrolandia
            'Hidrolandia' => [
                ['Algodão bolinha', 333],
                ['Álcool 70% (100ml)', 665],
                ['Gaze 13 fios 7,5cm x 7,5cm', 133],
                ['Luva de procedimento cirúrgico', 67],
                ['Máscara Cirúrgica', 133],
                ['Clorexidina Degermante 2% (100ml)', 998],
                ['Iodopovidona 10%', 665],
                ['Soro Fisiológico 0,9%  ampola', 133],
                ['Micropore', 665],
                ['Esparadrapo', 665],
                ['Curativo transparente (Band-Aid)', 133],
                ['Atadura crepom 10cm X 1,8m', 67],
                ['Atadura crepom 15cm X 1,8m', 67],
                ['Atadura crepom 20cm X 1,8m', 67],
                ['Lençol papel hospitalar Maca', 67],
                ['Descarpack 13L', 7],
                ['Benalet Pastilha', 33],
                ['Bilastina', 33],
                ['Buscofem', 33],
                ['Buscopan composto', 33],
                ['Cetoprofeno comp', 33],
                ['Cimegripe', 33],
                ['Decongex', 33],
                ['Diclofenaco Spray', 33],
                ['Dipirona', 33],
                ['Diprogenta Pomada', 33],
                ['Epocler', 33],
                ['Floratil (Enterogermina Plus)', 33],
                ['Ibuprofeno', 33],
                ['Nebacetin Pomada', 33],
                ['Nimesulida', 33],
                ['Omeprazol Comprimido', 33],
                ['Paracetamol', 33],
                ['Prednisolona Comprimido', 33],
                ['Torsilax', 33],
                ['Trok-N', 33],
                ['Vonau', 33],
            ],
            // São João do Meriti
            'São João do Meriti' => [
                ['Algodão bolinha', 551],
                ['Álcool 70% (100ml)', 1103],
                ['Gaze 13 fios 7,5cm x 7,5cm', 221],
                ['Luva de procedimento cirúrgico', 110],
                ['Máscara Cirúrgica', 221],
                ['Clorexidina Degermante 2% (100ml)', 1654],
                ['Iodopovidona 10%', 1103],
                ['Soro Fisiológico 0,9%  ampola', 221],
                ['Micropore', 1103],
                ['Esparadrapo', 1103],
                ['Curativo transparente (Band-Aid)', 221],
                ['Atadura crepom 10cm X 1,8m', 110],
                ['Atadura crepom 15cm X 1,8m', 110],
                ['Atadura crepom 20cm X 1,8m', 110],
                ['Lençol papel hospitalar Maca', 110],
                ['Descarpack 13L', 11],
                ['Benalet Pastilha', 55],
                ['Bilastina', 55],
                ['Buscofem', 55],
                ['Buscopan composto', 55],
                ['Cetoprofeno comp', 55],
                ['Cimegripe', 55],
                ['Decongex', 55],
                ['Diclofenaco Spray', 55],
                ['Dipirona', 55],
                ['Diprogenta Pomada', 55],
                ['Epocler', 55],
                ['Floratil (Enterogermina Plus)', 55],
                ['Ibuprofeno', 55],
                ['Nebacetin Pomada', 55],
                ['Nimesulida', 55],
                ['Omeprazol Comprimido', 55],
                ['Paracetamol', 55],
                ['Prednisolona Comprimido', 55],
                ['Torsilax', 55],
                ['Trok-N', 55],
                ['Vonau', 55],
            ],
            // Duque de Caxias
            'Duque de Caxias' => [
                ['Algodão bolinha', 235],
                ['Álcool 70% (100ml)', 470],
                ['Gaze 13 fios 7,5cm x 7,5cm', 94],
                ['Luva de procedimento cirúrgico', 47],
                ['Máscara Cirúrgica', 94],
                ['Clorexidina Degermante 2% (100ml)', 705],
                ['Iodopovidona 10%', 470],
                ['Soro Fisiológico 0,9%  ampola', 94],
                ['Micropore', 470],
                ['Esparadrapo', 470],
                ['Curativo transparente (Band-Aid)', 94],
                ['Atadura crepom 10cm X 1,8m', 47],
                ['Atadura crepom 15cm X 1,8m', 47],
                ['Atadura crepom 20cm X 1,8m', 47],
                ['Lençol papel hospitalar Maca', 47],
                ['Descarpack 13L', 5],
                ['Benalet Pastilha', 24],
                ['Bilastina', 24],
                ['Buscofem', 24],
                ['Buscopan composto', 24],
                ['Cetoprofeno comp', 24],
                ['Cimegripe', 24],
                ['Decongex', 24],
                ['Diclofenaco Spray', 24],
                ['Dipirona', 24],
                ['Diprogenta Pomada', 24],
                ['Epocler', 24],
                ['Floratil (Enterogermina Plus)', 24],
                ['Ibuprofeno', 24],
                ['Nebacetin Pomada', 24],
                ['Nimesulida', 24],
                ['Omeprazol Comprimido', 24],
                ['Paracetamol', 24],
                ['Prednisolona Comprimido', 24],
                ['Torsilax', 24],
                ['Trok-N', 24],
                ['Vonau', 24],
            ],
            // Franco da Rocha
            'Franco da Rocha' => [
                ['Algodão bolinha', 33],
                ['Álcool 70% (100ml)', 65],
                ['Gaze 13 fios 7,5cm x 7,5cm', 13],
                ['Luva de procedimento cirúrgico', 7],
                ['Máscara Cirúrgica', 13],
                ['Clorexidina Degermante 2% (100ml)', 98],
                ['Iodopovidona 10%', 65],
                ['Soro Fisiológico 0,9%  ampola', 13],
                ['Micropore', 65],
                ['Esparadrapo', 65],
                ['Curativo transparente (Band-Aid)', 13],
                ['Atadura crepom 10cm X 1,8m', 7],
                ['Atadura crepom 15cm X 1,8m', 7],
                ['Atadura crepom 20cm X 1,8m', 7],
                ['Lençol papel hospitalar Maca', 7],
                ['Descarpack 13L', 1],
                ['Benalet Pastilha', 3],
                ['Bilastina', 3],
                ['Buscofem', 3],
                ['Buscopan composto', 3],
                ['Cetoprofeno comp', 3],
                ['Cimegripe', 3],
                ['Decongex', 3],
                ['Diclofenaco Spray', 3],
                ['Dipirona', 3],
                ['Diprogenta Pomada', 3],
                ['Epocler', 3],
                ['Floratil (Enterogermina Plus)', 3],
                ['Ibuprofeno', 3],
                ['Nebacetin Pomada', 3],
                ['Nimesulida', 3],
                ['Omeprazol Comprimido', 3],
                ['Paracetamol', 3],
                ['Prednisolona Comprimido', 3],
                ['Torsilax', 3],
                ['Trok-N', 3],
                ['Vonau', 3],
            ],
            // PLAZA-SP
            'PLAZA-SP' => [
                ['Algodão bolinha', 1046],
                ['Álcool 70% (100ml)', 2093],
                ['Gaze 13 fios 7,5cm x 7,5cm', 419],
                ['Luva de procedimento cirúrgico', 209],
                ['Máscara Cirúrgica', 419],
                ['Clorexidina Degermante 2% (100ml)', 3139],
                ['Iodopovidona 10%', 2093],
                ['Soro Fisiológico 0,9%  ampola', 419],
                ['Micropore', 2093],
                ['Esparadrapo', 2093],
                ['Curativo transparente (Band-Aid)', 419],
                ['Atadura crepom 10cm X 1,8m', 209],
                ['Atadura crepom 15cm X 1,8m', 209],
                ['Atadura crepom 20cm X 1,8m', 209],
                ['Lençol papel hospitalar Maca', 209],
                ['Descarpack 13L', 21],
                ['Benalet Pastilha', 105],
                ['Bilastina', 105],
                ['Buscofem', 105],
                ['Buscopan composto', 105],
                ['Cetoprofeno comp', 105],
                ['Cimegripe', 105],
                ['Decongex', 105],
                ['Diclofenaco Spray', 105],
                ['Dipirona', 105],
                ['Diprogenta Pomada', 105],
                ['Epocler', 105],
                ['Floratil (Enterogermina Plus)', 105],
                ['Ibuprofeno', 105],
                ['Nebacetin Pomada', 105],
                ['Nimesulida', 105],
                ['Omeprazol Comprimido', 105],
                ['Paracetamol', 105],
                ['Prednisolona Comprimido', 105],
                ['Torsilax', 105],
                ['Trok-N', 105],
                ['Vonau', 105],
            ],
            // Campina Grande
            'Campina Grande' => [
                ['Algodão bolinha', 58],
                ['Álcool 70% (100ml)', 115],
                ['Gaze 13 fios 7,5cm x 7,5cm', 23],
                ['Luva de procedimento cirúrgico', 12],
                ['Máscara Cirúrgica', 23],
                ['Clorexidina Degermante 2% (100ml)', 173],
                ['Iodopovidona 10%', 115],
                ['Soro Fisiológico 0,9%  ampola', 23],
                ['Micropore', 115],
                ['Esparadrapo', 115],
                ['Curativo transparente (Band-Aid)', 23],
                ['Atadura crepom 10cm X 1,8m', 12],
                ['Atadura crepom 15cm X 1,8m', 12],
                ['Atadura crepom 20cm X 1,8m', 12],
                ['Lençol papel hospitalar Maca', 12],
                ['Descarpack 13L', 1],
                ['Benalet Pastilha', 6],
                ['Bilastina', 6],
                ['Buscofem', 6],
                ['Buscopan composto', 6],
                ['Cetoprofeno comp', 6],
                ['Cimegripe', 6],
                ['Decongex', 6],
                ['Diclofenaco Spray', 6],
                ['Dipirona', 6],
                ['Diprogenta Pomada', 6],
                ['Epocler', 6],
                ['Floratil (Enterogermina Plus)', 6],
                ['Ibuprofeno', 6],
                ['Nebacetin Pomada', 6],
                ['Nimesulida', 6],
                ['Omeprazol Comprimido', 6],
                ['Paracetamol', 6],
                ['Prednisolona Comprimido', 6],
                ['Torsilax', 6],
                ['Trok-N', 6],
                ['Vonau', 6],
            ],
        ];

// Primeiro, criamos todos os insumos únicos com suas unidades de medida
foreach ($this->insumosComMedida as $nomeInsumo => $unidadeMedida) {
    Insumo::firstOrCreate(
        ['nome' => $nomeInsumo],
        ['unidade_medida' => $unidadeMedida]
    );
}

// Agora, para cada unidade (team), relacionamos com seus insumos
foreach ($dados as $nomeUnidade => $insumos) {
    $team = Team::where('name', $nomeUnidade)->first();
    
    if (!$team) {
        continue; // Pula se a unidade não existir
    }

    foreach ($insumos as $insumo) {
        $nomeInsumo = $insumo[0];
        $quantidadeMinima = $insumo[1];

        $insumoModel = Insumo::where('nome', $nomeInsumo)->first();
        
        if ($insumoModel) {
            // Verifica se o relacionamento já existe
            $existe = DB::table('insumo_team')
                ->where('insumo_id', $insumoModel->id)
                ->where('team_id', $team->id)
                ->exists();

            if (!$existe) {
                $team->insumos()->attach($insumoModel->id, [
                    'quantidade_minima' => $quantidadeMinima,
                    'quantidade_existente' => 0 // Inicia com 0
                    // Removemos unidade_medida daqui pois já está na tabela insumos
                ]);
            }
        }
    }
}
    }
}