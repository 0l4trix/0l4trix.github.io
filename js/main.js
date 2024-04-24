// responsive form, nav
window.onload = function () {
	const gameHeight = 8;
	const gameWidth = 8;

	const container = document.getElementById("game-container");

	for(let i = 0; i < gameWidth * gameHeight; i++) {
	    container.innerHTML += `<div class="grid-square"></div>`;
	}

	const gridSquares = document.querySelectorAll(".grid-square");

	// Player Movement Start

	gridSquares[0].classList.add("contains-player");
	let playerX = 0,
	    playerY = 0;

		const upBtn = document.querySelector("#up");
		const leftBtn = document.querySelector("#left");
		const downBtn = document.querySelector("#down");
		const rightBtn = document.querySelector("#right");
		const allBtns = [upBtn, leftBtn, downBtn, rightBtn];

		// Situation Handler Start

	const prototype = document.querySelector("#prototype");
	const situations = document.querySelectorAll(".situations");
	const secondArrival = document.querySelector("#secondArrival");
	const forbiddenMove = document.querySelector("#forbiddenMove");
	let hasKey = document.querySelector("#hasKey");
	hasKey.disabled = true;
	// Still can't get it to recognise existing forbidden class
	situations[2].classList.add("forbidden");
	situations[3].classList.add("forbidden");
	situations[4].classList.add("forbidden");
	situations[10].classList.add("forbidden");
	situations[11].classList.add("forbidden");
	situations[12].classList.add("forbidden");
	situations[13].classList.add("forbidden");
	situations[14].classList.add("forbidden");
	situations[19].classList.add("forbidden");
	situations[20].classList.add("forbidden");
	situations[21].classList.add("forbidden");
	situations[22].classList.add("forbidden");
	situations[24].classList.add("forbidden");
	situations[32].classList.add("forbidden");
	situations[36].classList.add("forbidden");
	situations[40].classList.add("forbidden");
	situations[45].classList.add("forbidden");
	situations[48].classList.add("forbidden");
	situations[53].classList.add("forbidden");
	situations[54].classList.add("forbidden");
	situations[56].classList.add("forbidden");
	situations[57].classList.add("forbidden");
	situations[61].classList.add("forbidden");
	
	const start = () => {
		let prevSit;
		return function() {
		let n = (playerY * gameWidth) + playerX;
		secondArrival.classList.contains("seenSituation") ? secondArrival.classList.remove("seenSituation") : null;
		forbiddenMove.classList.contains("seenSituation") ? forbiddenMove.classList.remove("seenSituation") : null;
		if (situations[n].classList.contains("pastSituation")) {
			prevSit === n ? forbiddenMove.classList.add("seenSituation") : secondArrival.classList.add("seenSituation");
		} else {
			situations[n].classList.add("pastSituation");
			situations[n].classList.add("seenSituation");
			upBtn.disabled = true;
			leftBtn.disabled = true;
			downBtn.disabled = true;
			rightBtn.disabled = true;
		}
		situations[5].classList.contains("seenSituation") ? hasKey.disabled = false : null;
		if (typeof prevSit === 'number') {situations[prevSit].classList.remove("seenSituation");}
		prevSit = n;
		}
	}
	
	prototype.addEventListener("click", start());

	function simulateClick() {
	  prototype.click();
	}
	
	simulateClick();

	const secondPhaseTrigger = document.querySelectorAll(".secondPhaseTrigger");
	const thirdPhaseTriggerA = document.querySelectorAll(".thirdPhaseTriggerA");
	const thirdPhaseTriggerB = document.querySelectorAll(".thirdPhaseTriggerB");
	const secondPhase = document.querySelectorAll(".secondPhase");
	const thirdPhaseA = document.querySelectorAll(".thirdPhaseA");
	const thirdPhaseB = document.querySelectorAll(".thirdPhaseB");

	for(let i = 0; i < secondPhaseTrigger.length; i++) {
		secondPhaseTrigger[i].classList.add("reveal");
		secondPhaseTrigger[i].addEventListener("click", switchSit());

		function switchSit () {
			return function() {
			secondPhaseTrigger[i].classList.remove("reveal");
			secondPhase[i].classList.add("reveal");
			thirdPhaseTriggerA[i].classList.add("reveal");
			thirdPhaseTriggerB[i].classList.add("reveal");
		}}
	}

	for(let i = 0; i < thirdPhaseTriggerA.length; i++) {
		thirdPhaseTriggerA[i].addEventListener("click", switchSit1());

		function switchSit1 () {
			return function() {
			thirdPhaseTriggerA[i].classList.remove("reveal");
			thirdPhaseTriggerB[i].classList.remove("reveal");
			thirdPhaseA[i].classList.add("reveal");
		}}
	}

	for(let i = 0; i < thirdPhaseTriggerB.length; i++) {
		thirdPhaseTriggerB[i].addEventListener("click", switchSit2());

		function switchSit2 () {
			return function() {
			thirdPhaseTriggerA[i].classList.remove("reveal");
			thirdPhaseTriggerB[i].classList.remove("reveal");
			thirdPhaseB[i].classList.add("reveal");
		}}
	}

		// Situation Handler End
		let oldX;
		let oldY;

		const textContainer = document.querySelector("#textContainer");

	function move(direction) {
	    const playerDiv = document.querySelector(".grid-square.contains-player");
	    playerDiv.classList.remove("contains-player");

	    let newPlayerDiv;

	    switch(direction) {
	        case "left": if (playerX > 0) {playerX--; break;} else {break;};
	        case "up": if (playerY > 0) {playerY--; break;} else {break;};
	        case "right": if (playerX < 7) {playerX++; break;} else {break;};
	       	case "down": if (playerY < 7) {playerY++; break;} else {break;};
	    }

		if (situations[(playerY * gameWidth) + playerX].classList.contains("forbidden")) {
			playerX = oldX;
			playerY = oldY;
		}
			newPlayerDiv = gridSquares[(playerY * gameWidth) + playerX];
			newPlayerDiv.classList.add("contains-player");
			oldX = playerX;
			oldY = playerY;
			textContainer.scrollTop = 0;
		}

	for(let i = 0; i < allBtns.length; i++) {
		allBtns[i].addEventListener("click", onBtnPress());

	function onBtnPress() {
		return function () {
		let direction;
		if (allBtns[i] === allBtns[0]) {direction = "up";}
		else if (allBtns[i] === allBtns[1]) {direction = "left";}
		else if (allBtns[i] === allBtns[2]) {direction = "down";}
		else {direction = "right";}

	    if(direction) {
	    	move(direction);
	    }
		simulateClick();}
	};
  	}

	const continueBtn = document.querySelectorAll(".continueBtn");
  
	  for(let i = 0; i < continueBtn.length; i++) {
		  continueBtn[i].addEventListener("click", continueBtnPress());
	  
		  function continueBtnPress () {
			return function () {
			  upBtn.disabled = false;
			  leftBtn.disabled = false;
			  downBtn.disabled = false;
			  rightBtn.disabled = false;}
		  }
	  }

	// Player Movement End
}

// Riddle Generator Start

const riddle = document.querySelector("#riddle");
const riddle1 = document.querySelector("#riddle1");
const userAnswer = document.querySelector("#userAnswer");
const userAnswer1 = document.querySelector("#userAnswer1");
const riddleBtn = document.querySelector("#riddleBtn");
const riddleBtn1 = document.querySelector("#riddleBtn1");
const answerBtn = document.querySelector("#answerBtn");
const answerBtn1 = document.querySelector("#answerBtn1");
const answerCorrect = document.querySelector("#answerCorrect");
const answerCorrect1 = document.querySelector("#answerCorrect1");
const riddleDeath = document.querySelector("#riddleDeath");

const riddles = [
  {
    riddle: "You measure my life in hours and I serve you by expiring. I'm quick when I'm thin and slow when I'm fat. The wind is my enemy. What am I?",
    answer: ["candle", "a candle", "candle.", "a candle."],
  },
  {
    riddle: "I have cities, but no houses. I have mountains, but no trees. I have water, but no fish. What am I?",
    answer: ["map", "a map", "map.", "a map."]
  },
  {
    riddle: "What is so fragile that saying its name breaks it?",
    answer: ["silence", "the silence", "silence.", "the silence."],
  },
  {
    riddle: "I have keys, but no locks. I have space, but no rooms. You can enter, but you can't go outside. What am I?",
    answer: ["keyboard", "a keyboard", "keyboard.", "a keyboard."],
  },
  {
    riddle: "What can you put in a bucket to make it weigh less?",
    answer: ["hole", "a hole", "hole.", "a hole."],
  },
  {
    riddle: "What is the only place where today comes before yesterday?",
    answer: ["dictionary", "a dictionary", "the dictionary", "dictionary.", "a dictionary.", "the dictionary."],
  },
  {
    riddle: "Poor people have it. Rich people need it. If you eat it you die. What is it?",
    answer : ["nothing", "nothing."],
  },
  {
    riddle: "I am an odd number. Take away a letter and I become even. What number am I?",
    answer : ["seven", "seven."],
  },
  {
    riddle: "Forward, I am heavy; backward, I am not. What am I?",
    answer : ["ton", "a ton", "ton.", "a ton."],
  },
  {
    riddle: "Where can you finish a book without finishing a sentence?",
    answer : ["prison", "in prison", "in the prison", "prison.", "in prison.", "in the prison."],
  },
];

const rGenExtra = () => {
  userAnswer.value = null;
  userAnswer.classList.add("reveal");}

  const rGenExtra1 = () => {
	userAnswer1.value = null;
	userAnswer.classList.remove("reveal");
	userAnswer1.classList.add("reveal");}

let prevRand;

let currentAnswers;

const riddleGenerator = () => {
	var randomNum = Math.floor(Math.random() * riddles.length);
  
	while (randomNum === prevRand) {
	  randomNum = Math.floor(Math.random() * riddles.length);
	}
  
	if (userAnswer.classList.contains("reveal")) {
	riddle.textContent = riddles[randomNum].riddle;
	} else if (userAnswer1.classList.contains("reveal")) {
	riddle1.textContent = riddles[randomNum].riddle;
	}
	currentAnswers = riddles[randomNum].answer;
  
	prevRand = randomNum;
  };

const answerChecker = () => {
	for (let i = 0; i < currentAnswers.length; i++) {
	  if (userAnswer.value.toLowerCase() === currentAnswers[i]) {
	  answerCorrect.classList.add("reveal");
	  }
	}

	if (answerCorrect.classList.contains("reveal")) {
		null;
	} else if (answerBtn.classList.contains("warning")) {
	  riddleDeath.classList.add("reveal");
	} else {
	  alert("You answered incorrectly. Careful now, this is your last chance!");
	  answerBtn.classList.add("warning");
	}
  }

  const answerChecker1 = () => {
	for (let i = 0; i < currentAnswers.length; i++) {
	  if (userAnswer1.value.toLowerCase() === currentAnswers[i]) {
	  answerCorrect1.classList.add("reveal");
	  }
	}

	if (answerCorrect1.classList.contains("reveal")) {
	  null;
	} else {
	  riddleDeath.classList.add("reveal");
	}
  
  }

riddleBtn.addEventListener("click", rGenExtra);
riddleBtn.addEventListener("click", riddleGenerator);
riddleBtn1.addEventListener("click", rGenExtra1);
riddleBtn1.addEventListener("click", riddleGenerator);
answerBtn.addEventListener("click", answerChecker);
answerBtn1.addEventListener("click", answerChecker1);

// Riddle Generator End

// Game Restart Start

const restart = document.querySelector("#restart");
const glitch = document.querySelector("#glitchContainer");

restart.addEventListener("click", restartGame);
glitch.addEventListener("click", restartGame);

function restartGame () {
	sessionStorage.setItem('reloaded', true);
	window.location.reload();
}

// Game Restart End
