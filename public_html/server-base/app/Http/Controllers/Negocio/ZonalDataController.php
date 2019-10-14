<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
use App\Agreement;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ZonalDataController extends Controller
{
    private $zonales = '[
        {
            "id": 1,
            "name": "ZONA DE PLANIFICACIÓN 1 - NORTE: ESMERALDAS: Esmeraldas",
            "telefono": "<a href=\"tel:062011446\">062011446</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, ESMERALDAS, Esmeraldas, Av. Del Pacífico, Malecón de Las Palmas\" target=\"blank\">Av. Del Pacífico, Malecón de Las Palmas (Frente Patio Comidas)</a>"
        },
        {
            "id": 2,
            "name": "ZONA DE PLANIFICACIÓN 1 - NORTE: CARCHI: Tulcán",
            "telefono": "<a href=\"tel:022960046\">022960046</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, CARCHI, Tulcán, Guayaquil N39-008 y Av. Manabí\" target=\"blank\">Guayaquil N39-008 y Av. Manabí, Edificio Cámara de Comercio piso 2</a>"
        },
        {
            "id": 3,
            "name": "ZONA DE PLANIFICACIÓN 1 - NORTE: IMBABURA: Ibarra",
            "telefono": "<a href=\"tel:062955711  \">062955711  </a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, IMBABURA, Ibarra, Sucre 1472 y Av. Teodoro Gómez\" target=\"blank\">Sucre 1472 y Av. Teodoro Gómez</a>"
        },
        {
            "id": 4,
            "name": "ZONA DE PLANIFICACIÓN 1 - NORTE: IMBABURA: Ibarra",
            "telefono": "<a href=\"tel:062958547\">062958547</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, IMBABURA, Ibarra, Sucre 1472 y Av. Teodoro Gómez\" target=\"blank\">Sucre 1472 y Av. Teodoro Gómez</a>"
        },
        {
            "id": 5,
            "name": "ZONA DE PLANIFICACIÓN 1 - NORTE: IMBABURA: Ibarra",
            "telefono": "<a href=\"tel:062958759 Ext. 2631\">062958759 Ext. 2631</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, IMBABURA, Ibarra, Sucre 1472 y Av. Teodoro Gómez\" target=\"blank\">Sucre 1472 y Av. Teodoro Gómez</a>"
        },
        {
            "id": 6,
            "name": "ZONA DE PLANIFICACIÓN 1 - NORTE: SUCUMBÍOS: Nueva Loja",
            "telefono": "<a href=\"tel:062991914\">062991914</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, SUCUMBÍOS, Nueva Loja, Av. Monseñor Gonzalo López Marañón y Av. Circunvalación\" target=\"blank\">Centro de Atención Ciudadana Av. Monseñor Gonzalo López Marañón y Av. Circunvalación, Oficina # 18, piso 1°</a>"
        },
        {
            "id": 7,
            "name": "ZONA DE PLANIFICACIÓN 2 - CENTRO NORTE: PICHINCHA: Quito ",
            "telefono": "<a href=\"tel:023999333\">023999333</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, PICHINCHA, Quito , Av. Gran Colombia y Briceño Edificio La Licuadora\" target=\"blank\">Av. Gran Colombia y Briceño Edificio La Licuadora, Mezanine</a>"
        },
        {
            "id": 8,
            "name": "ZONA DE PLANIFICACIÓN 2 - CENTRO NORTE: ORELLANA: Orellana",
            "telefono": "<a href=\"tel:062881583\">062881583</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, ORELLANA, Orellana, Juan Montalvo entre 9 de Octubre y 6 de Diciembre\" target=\"blank\">Juan Montalvo entre 9 de Octubre y 6 de Diciembre( junto al archivo del Concejo de la Judicatura de Orellana)</a>"
        },
        {
            "id": 9,
            "name": "ZONA DE PLANIFICACIÓN 2 - CENTRO NORTE: NAPO: Tena",
            "telefono": "<a href=\"tel:062886536\">062886536</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, NAPO, Tena, César Augusto Rueda sector El Balneario\" target=\"blank\">César Augusto Rueda sector El Balneario - Junto Hotel YUTZOS</a>"
        },
        {
            "id": 10,
            "name": "ZONA DE PLANIFICACIÓN 3 - CENTRO: CHIMBORAZO: Riobamba",
            "telefono": "<a href=\"tel:032946682\">032946682</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, CHIMBORAZO, Riobamba, Av. Daniel León Borja y Jacinto González\" target=\"blank\">Av. Daniel León Borja y Jacinto González, esquina. Edificio Zurich, 3er piso</a>"
        },
        {
            "id": 11,
            "name": "ZONA DE PLANIFICACIÓN 3 - CENTRO: COTOPAXI: Latacunga",
            "telefono": "<a href=\"tel:032245700\">032245700</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, COTOPAXI, Latacunga, Marqués de Maenza y Quito\" target=\"blank\">Marqués de Maenza y Quito, Edificio Centro de Atención Ciudadana, El Rosal</a>"
        },
        {
            "id": 12,
            "name": "ZONA DE PLANIFICACIÓN 3 - CENTRO: TUNGURAHUA: Ambato",
            "telefono": "<a href=\"tel:032821800\">032821800</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, TUNGURAHUA, Ambato, Guayaquil 01-08 y Rocafuerte\" target=\"blank\">Guayaquil 01-08 y Rocafuerte junto Hotel Ambato</a>"
        },
        {
            "id": 13,
            "name": "ZONA DE PLANIFICACIÓN 3 - CENTRO: PASTAZA: Pastaza",
            "telefono": "<a href=\"tel:032740123\">032740123</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, PASTAZA, Pastaza, Km. 2 ½ vía al Tena, Universidad Estatal Amazónica\" target=\"blank\">Km. 2 ½ vía al Tena, Universidad Estatal Amazónica</a>"
        },
        {
            "id": 14,
            "name": "ZONA DE PLANIFICACIÓN 4 - PACÍFICO: MANABÍ: Portoviejo",
            "telefono": "<a href=\"tel:053043711 \">053043711 </a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, MANABÍ, Portoviejo, Los Nardos y Av. 15 de Abril \" target=\"blank\">Los Nardos y Av. 15 de Abril detrás del ECU 911 Edificio Centro de Atención Ciudadana, Piso 3</a>"
        },
        {
            "id": 15,
            "name": "ZONA DE PLANIFICACIÓN 4 - PACÍFICO: MANABÍ: Portoviejo",
            "telefono": "<a href=\"tel:053043734\">053043734</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, MANABÍ, Portoviejo, Los Nardos y Av. 15 de Abril \" target=\"blank\">Los Nardos y Av. 15 de Abril detrás del ECU 911 Edificio Centro de Atención Ciudadana, Piso 3</a>"
        },
        {
            "id": 16,
            "name": "ZONA DE PLANIFICACIÓN 4 - PACÍFICO: SANTO DOMINGO DE LOS TSÁCHILAS: Santo Domingo",
            "telefono": "<a href=\"tel:022745726\">022745726</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, SANTO DOMINGO DE LOS TSÁCHILAS, Santo Domingo, Av. Esmeraldas y Monseñor Emilio Lorenzo Sthele\" target=\"blank\">Av. Esmeraldas y Monseñor Emilio Lorenzo Sthele, Edificio Santo Domingo Plaza, piso 3 Of. 301</a>"
        },
        {
            "id": 17,
            "name": "ZONA DE PLANIFICACIÓN 5 - LITORAL: GUAYAS.- Guayaquil",
            "telefono": "<a href=\"tel:042068508\">042068508</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, GUAYAS.- Guayaquil, Av. Francisco de Orellana y Justino Cornejo\" target=\"blank\">Av. Francisco de Orellana y Justino Cornejo, Edificio del Gobierno Zonal de Guayaquil, piso 8</a>"
        },
        {
            "id": 18,
            "name": "ZONA DE PLANIFICACIÓN 5 - LITORAL: LOS RÍOS: Babahoyo",
            "telefono": "<a href=\"tel:052739322\">052739322</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, LOS RÍOS, Babahoyo, Av. 10 de Agosto entre Eloy Alfaro y Rocafuerte \" target=\"blank\">Av. 10 de Agosto entre Eloy Alfaro y Rocafuerte Edificio Cooperativa Policía Nacional, piso 2</a>"
        },
        {
            "id": 19,
            "name": "ZONA DE PLANIFICACIÓN 5 - LITORAL: SANTA ELENA: Salinas",
            "telefono": "<a href=\"tel:043728904\">043728904</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, SANTA ELENA, Salinas, Av. Carlos Espinoza Larrea y Calle 5\" target=\"blank\">Edificio Centro de Atención Ciudadana Av. Carlos Espinoza Larrea y Calle 5, Planta Alta</a>"
        },
        {
            "id": 20,
            "name": "ZONA DE PLANIFICACIÓN 5 - LITORAL: BOLÍVAR: Guaranda",
            "telefono": "<a href=\"tel:032551249\">032551249</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, BOLÍVAR, Guaranda, Sucre y García Moreno\" target=\"blank\">Sucre y García Moreno Edificio Gobernación Planta Baja</a>"
        },
        {
            "id": 21,
            "name": "ZONA DE PLANIFICACIÓN 6 - AUSTRO: AZUAY: Cuenca",
            "telefono": "<a href=\"tel:072838209\">072838209</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, AZUAY, Cuenca, Simón Bolívar y Juan Montalvo\" target=\"blank\">Simón Bolívar y Juan Montalvo. Edificio Casa del Coco, Piso 1</a>"
        },
        {
            "id": 22,
            "name": "ZONA DE PLANIFICACIÓN 6 - AUSTRO: CAÑAR: Azogues",
            "telefono": "<a href=\"tel:072838209\">072838209</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, CAÑAR, Azogues, Av. 16 de Abril y Babahoyo\" target=\"blank\">Av. 16 de Abril y Babahoyo</a>"
        },
        {
            "id": 23,
            "name": "ZONA DE PLANIFICACIÓN 6 - AUSTRO: MORONA SANTIAGO: Morona",
            "telefono": "<a href=\"tel:072701480\">072701480</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, MORONA SANTIAGO, Morona, Bolívar entre Amazonas y Soasti\" target=\"blank\">Morona.- Bolívar entre Amazonas y Soasti</a>"
        },
        {
            "id": 24,
            "name": "ZONA DE PLANIFICACIÓN 7- SUR: LOJA: Loja",
            "telefono": "<a href=\"tel:072572964\">072572964</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, LOJA, Loja, Sucre 191-31 y Quito \" target=\"blank\">Sucre 191-31 y Quito esquina Edificio Patrimonio Cultural</a>"
        },
        {
            "id": 25,
            "name": "ZONA DE PLANIFICACIÓN 7- SUR: EL ORO: Machala",
            "telefono": "<a href=\"tel:072968480\">072968480</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, EL ORO, Machala, Rocafuerte 722 entre Junín y Tarqui\" target=\"blank\"> Rocafuerte 722 entre Junín y Tarqui</a>"
        },
        {
            "id": 26,
            "name": "ZONA DE PLANIFICACIÓN 7- SUR: ZAMORA CHINCHIPE: Zamora",
            "telefono": "<a href=\"tel:072606466\">072606466</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, ZAMORA CHINCHIPE, Zamora, José Luis Tamayo y Diego de Vaca\" target=\"blank\">José Luis Tamayo y Diego de Vaca Edificio De la Gobernación Piso 1</a>"
        },
        {
            "id": 27,
            "name": "ZONA DE PLANIFICACIÓN INSULAR: SANTA CRUZ",
            "telefono": "<a href=\"tel:052527135 \">052527135 </a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, SANTA CRUZ, Av. Charles Darwin y 12 de Febrero Nro. 399\" target=\"blank\">Av. Charles Darwin y 12 de Febrero Nro. 399. Edificio  CAPTURGAL  piso 1 y 2</a>"
        },
        {
            "id": 28,
            "name": "ZONA DE PLANIFICACIÓN INSULAR: SANTA CRUZ",
            "telefono": "<a href=\"tel:052526174\">052526174</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, SANTA CRUZ, Av. Charles Darwin y 12 de Febrero Nro. 399\" target=\"blank\">Av. Charles Darwin y 12 de Febrero Nro. 399. Edificio  CAPTURGAL  piso 1 y 2</a>"
        },
        {
            "id": 29,
            "name": "ZONA DE PLANIFICACIÓN INSULAR: ISABELA",
            "telefono": "<a href=\"tel:052529132\">052529132</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, ISABELA, Av. Antonio Gil y Petreles\" target=\"blank\">Av. Antonio Gil y Petreles</a>"
        },
        {
            "id": 30,
            "name": "ZONA DE PLANIFICACIÓN INSULAR: SAN CRISTÓBAL",
            "telefono": "<a href=\"tel:052520704\">052520704</a>",
            "direccion": "<a href=\"http://maps.google.com/maps?q=Ecuador, SAN CRISTÓBAL, Av. Alsacio Northia y José Vallejo Esquina\" target=\"blank\"> Av. Alsacio Northia y José Vallejo Esquina</a>"
        }
    ]';

    function get(Request $request)
    {
       $data = json_decode($this->zonales);
       $id = $request['id'];
       if ($id == null) {
          return response()->json($data,200);
       } else {
           foreach($data as $zonal) {
               if ($id == $zonal->id) {
                $attach = [];
                return response()->json(["Zonal"=>$zonal, "attach"=>$attach],200);       
               }
           }
       }
    }
}