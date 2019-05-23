<pre>
<?php

require_once __DIR__."/php/class/quiz.php";

$q = quiz::newByID(1);
for($i=0;$i<=4;$i++){
	$p = new perguntas([
		'enunciado' => 'pergunta'.$i
	]);
	for($j=0;$j<=$i+1;$j++){
		$r = new respostas([
			'resposta' => 'resposta'.$i.$j,
			'certa' => 0
		]);
		if($i === $j){
			$r->set('certa',1);
		}
		$p->add('respostas', $r);
	}
	$q->add('perguntas', $p);
}
$q->insert();
print_r($q);

/*
var_dump($u->insert());
print_r($u);*/
/*
$u = new quiz();
$u->fill([
	'titulo' => 'quem matou o bozo?',
	'idcategoria' => 2,
	'descricao' => 'asukoyhfuiosydfuiosyfsuiod',
	'perguntas' => [
		1,
		new perguntas([
			'id' => 1,
			'enunciado' => 'aasasdasdasd',
			'respostas' => [
				new respostas([
					'resposta' => 'resposta1',
					'certa' => 0
				]),
				new respostas([
					'resposta' => 'resposta2',
					'certa' => 1
				]),
				new respostas([
					'resposta' => 'resposta3',
					'certa' => 0
				])
			]
		])
	]
]);

print_r($u);
*/
?>
</pre>
