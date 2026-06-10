<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mision;
use App\Models\FaseMision;

class MisionesSeeder extends Seeder
{
    public function run(): void
    {

        // MISIÓN 1 — El agua de los apus
        $mision1 = Mision::create([
            'titulo'               => 'El agua de los apus',
            'slug'                 => 'el-agua-de-los-apus',
            'descripcion'          => 'Investiga por qué el agua del río baja turbia después de las lluvias en tu comunidad andina.',
            'contexto_andino'      => 'En las comunidades de Huancavelica, los ríos son fuente de vida. Pero después de las lluvias, el agua cambia. Los abuelos dicen que los apus lloran cuando la tierra está herida. ¿Qué está pasando realmente?',
            'pregunta_investigacion' => '¿Por qué el agua del río baja turbia después de las lluvias en nuestra comunidad?',
            'orden'                => 1,
            'activa'               => true,
        ]);

        $fases1 = [
            [
                'nombre'         => 'Problematización',
                'nombre_quechua' => 'Tapukuy',
                'instruccion'    => 'Observa el río de tu comunidad después de una lluvia. ¿Qué cambios notas? Describe lo que ves, hueles o sientes. Escribe tu observación con el mayor detalle posible.',
                'pista_tupaq'    => 'Yachay wawqey, mira con cuidado el color del agua, la velocidad con que corre y qué materiales arrastra. Los abuelos observaban estas señales para saber cómo estaba la tierra.',
                'orden'          => 1,
                'xp_recompensa'  => 15,
            ],
            [
                'nombre'         => 'Hipótesis',
                'nombre_quechua' => 'Yuyaychakuy',
                'instruccion'    => 'Basándote en tu observación, escribe una hipótesis. ¿Por qué crees que el agua baja turbia? Usa la estructura: "Creo que... porque..."',
                'pista_tupaq'    => 'Una buena hipótesis conecta lo que observaste con una causa posible. Piensa: ¿qué pasa con la tierra de las laderas cuando llueve fuerte? ¿Qué llevan los ríos consigo?',
                'orden'          => 2,
                'xp_recompensa'  => 20,
            ],
            [
                'nombre'         => 'Recojo de datos',
                'nombre_quechua' => 'Hap\'iy',
                'instruccion'    => 'Diseña un plan para recoger evidencias. ¿Qué observarías? ¿Dónde? ¿Cuándo? ¿Con qué materiales simples podrías medir o registrar lo que pasa?',
                'pista_tupaq'    => 'Los científicos andinos usaban quipus para registrar datos. Tú puedes usar un cuaderno, fotos o muestras de agua en botellas. Piensa en comparar el agua antes y después de la lluvia.',
                'orden'          => 3,
                'xp_recompensa'  => 25,
            ],
            [
                'nombre'         => 'Análisis',
                'nombre_quechua' => 'Yachaqay',
                'instruccion'    => 'Con los datos que recogiste (o imaginas haber recogido), analiza: ¿qué relación encuentras entre la lluvia y la turbidez del agua? ¿Tu hipótesis fue correcta?',
                'pista_tupaq'    => 'Compara lo que predijiste con lo que encontraste. Si hay diferencias, eso también es valioso. La ciencia avanza cuando nos equivocamos y aprendemos por qué.',
                'orden'          => 4,
                'xp_recompensa'  => 25,
            ],
            [
                'nombre'         => 'Conclusión',
                'nombre_quechua' => 'Tukuchiy',
                'instruccion'    => 'Escribe tu conclusión final. ¿Qué aprendiste? ¿Cómo afecta esto a tu comunidad? ¿Qué podrían hacer los pobladores para cuidar el agua de los apus?',
                'pista_tupaq'    => 'Una buena conclusión responde la pregunta de investigación, menciona si la hipótesis fue confirmada y propone algo útil para la comunidad. ¡Tu conocimiento puede ayudar a tu pueblo!',
                'orden'          => 5,
                'xp_recompensa'  => 30,
            ],
        ];

        foreach ($fases1 as $fase) {
            FaseMision::create(array_merge($fase, ['mision_id' => $mision1->id]));
        }

        // MISIÓN 2 — Las papas y el helaje
        $mision2 = Mision::create([
            'titulo'               => 'Las papas y el helaje',
            'slug'                   => 'las-papas-y-el-helaje',
            'descripcion'          => 'Investiga cómo el frío intenso de las noches andinas afecta el crecimiento de las plantas.',
            'contexto_andino'      => 'En las alturas de Huancavelica, las heladas son parte de la vida. Los agricultores conocen el helaje como una amenaza para sus cultivos. Pero también saben que el frío puede ser aliado — el chuño nace del helaje. ¿Qué le pasa realmente a la planta cuando hiela?',
            'pregunta_investigacion' => '¿Cómo afecta el frío intenso de las noches andinas al crecimiento de las plantas de papa?',
            'orden'                => 2,
            'activa'               => true,
        ]);

        $fases2 = [
            [
                'nombre'         => 'Problematización',
                'nombre_quechua' => 'Tapukuy',
                'instruccion'    => 'Piensa en lo que sabes sobre las heladas en tu comunidad. ¿Qué le pasa a las plantas cuando hiela fuerte? Describe con detalle qué observas en los cultivos después de una helada.',
                'pista_tupaq'    => 'Yachay wawqey, los agricultores de los Andes han convivido con el helaje por miles de años. Observa las hojas, los tallos y los tubérculos. ¿Qué cambios notas en ellos después del frío?',
                'orden'          => 1,
                'xp_recompensa'  => 15,
            ],
            [
                'nombre'         => 'Hipótesis',
                'nombre_quechua' => 'Yuyaychakuy',
                'instruccion'    => 'Formula tu hipótesis: ¿cómo crees que el frío afecta el crecimiento de la papa? Escribe al menos dos posibles explicaciones usando "Creo que... porque..."',
                'pista_tupaq'    => 'Piensa en lo que el agua hace cuando se congela. ¿Qué pasa dentro de las células de la planta si el agua dentro de ellas se congela? Conecta ese proceso con lo que ves en los cultivos.',
                'orden'          => 2,
                'xp_recompensa'  => 20,
            ],
            [
                'nombre'         => 'Recojo de datos',
                'nombre_quechua' => 'Hap\'iy',
                'instruccion'    => 'Diseña un experimento sencillo que puedas hacer en casa o en la escuela para probar cómo el frío afecta a las plantas. Describe paso a paso qué harías, qué medirías y cómo registrarías los resultados.',
                'pista_tupaq'    => 'Un experimento justo compara dos situaciones iguales donde solo cambias una cosa — en este caso la temperatura. Podrías usar dos plantas iguales: una en un lugar frío y otra en temperatura normal. ¿Qué variables controlarías?',
                'orden'          => 3,
                'xp_recompensa'  => 25,
            ],
            [
                'nombre'         => 'Análisis',
                'nombre_quechua' => 'Yachaqay',
                'instruccion'    => 'Analiza los resultados de tu experimento (real o imaginado). ¿Qué diferencias encontraste entre la planta expuesta al frío y la que no? ¿Qué conclusión preliminar puedes sacar?',
                'pista_tupaq'    => 'Busca patrones en tus datos. ¿El daño fue igual en todas las partes de la planta? ¿Hubo una temperatura límite? ¿Las variedades nativas de papa resistieron mejor? Estas preguntas te ayudan a profundizar el análisis.',
                'orden'          => 4,
                'xp_recompensa'  => 25,
            ],
            [
                'nombre'         => 'Conclusión',
                'nombre_quechua' => 'Tukuchiy',
                'instruccion'    => 'Escribe tu conclusión final. ¿Cómo afecta el helaje a la papa? ¿Qué estrategias tradicionales andinas conoces para proteger los cultivos? ¿Cómo conecta la ciencia con el conocimiento ancestral?',
                'pista_tupaq'    => 'Los Andes son la cuna de la papa — hay más de 3000 variedades. Los agricultores andinos desarrollaron el chuño precisamente aprovechando el helaje. Tu conclusión puede conectar la ciencia moderna con esa sabiduría ancestral.',
                'orden'          => 5,
                'xp_recompensa'  => 30,
            ],
        ];

        foreach ($fases2 as $fase) {
            FaseMision::create(array_merge($fase, ['mision_id' => $mision2->id]));
        }
    }
}