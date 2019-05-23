function start_loading(){
	$.mobile.loading( "show", {
			text: "",
			textVisible: true,
			theme: "b",
			textonly: false,
			html: ""
	});
}

function stop_loading(){
	$.mobile.loading( "hide" );
}

$.fn.chooseAnswer = function(tentativa){
	
	var t = $(this),
		p = t.parents('.quiz-question');
		
	start_loading();
	
	tentativa.respostas[p.attr('question-id')] = t.attr('answer-id');
	
	if(p.next('.quiz-question').length){
		var next = p.next('.quiz-question');
		next.one('question-loaded', stop_loading());
		next.activeQuestion(tentativa);
	}
			
}
	
$.fn.activeQuestion = function(tentativa){
	var t = $(this);
	$('.quiz-question').removeClass('active');
	t.addClass('active');
	t.find('.question-answers .answer').each(function(){
		if(t.next('.quiz-question').length){
			$(this).one('click',function(){
				t.next('.quiz-question').activeQuestion(tentativa);
			});
		}
	});
	t.trigger('question-loaded');
}

class Resposta{
	constructor(id = 0, resposta = ''){
		this.id = id;
		this.resposta = resposta;
	}
}

class Pergunta{
	constructor(id = 0, pergunta = '', respostas = []){
		this.id = id;
		this.respostas = [];
		this.pergunta = pergunta;
		$.each(respostas, function(resposta){
			this.addResposta(resposta);
		});
	}
	addResposta(resposta){
		if(resposta instanceof Resposta !== true){
			var given = (typeof resposta == "object") ? resposta.constructor.name : typeof resposta;
			console.log("Objeto do tipo Resposta esperado, "+given+" recebido");
			return;
		}
		this.respostas.push(resposta);
	}
}

class Quiz{
	constructor(id = 0, titulo = '', perguntas = []){
		this.id = id;
		this.titulo = titulo;
		this.perguntas = [];
		$.each(perguntas,function(pergunta){
			this.addResposta(pergunta)
		});
	}
	addPergunta(pergunta){
		if(pergunta instanceof Pergunta !== true){
			var given = (typeof pergunta == "object") ? pergunta.constructor.name : typeof pergunta;
			console.log("Objeto do tipo Pergunta esperado, "+given+" recebido");
			return;
		}
		this.perguntas.push(pergunta);
	}
}

class Tentativa{
	constructor(quiz = new Quiz()){
		this.quiz = quiz;
		var respostas = {};
		$.each(this.quiz.perguntas, function(){
			var t = this;
			respostas[t.id] = 0;
		});
		this.respostas = respostas;
	}
}