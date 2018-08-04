<template>
  <div class="cssload-container">
    <div class="cssload-thing"></div>
    <div class="cssload-thing"></div>
    <div class="cssload-thing"></div>
    <div class="cssload-thing"></div>
    <div class="cssload-thing"></div>
    <div class="cssload-thing"></div>
    <div class="cssload-thing"></div>
    <div class="cssload-thing"></div>
    <div class="cssload-thing"></div>
    <div class="cssload-thing"></div>
    <div class="cssload-thing"></div>
    <div class="cssload-thing"></div>
  </div>
</template>

<style>
  .cssload-container {
  	position: absolute;
    top: 50%;
  	left: 50%;
  	transform: translate(-50%, -50%);
  		-o-transform: translate(-50%, -50%);
  		-ms-transform: translate(-50%, -50%);
  		-webkit-transform: translate(-50%, -50%);
  		-moz-transform: translate(-50%, -50%);
  }

  .cssload-thing {
  	height: 11px;
  	width: 2px;
  	background: rgb(187, 187, 187);
  	position: absolute;
  	animation: somecssload-thing_maybe_cool_idk 1.7s infinite ease;
  		-o-animation: somecssload-thing_maybe_cool_idk 1.7s infinite ease;
  		-ms-animation: somecssload-thing_maybe_cool_idk 1.7s infinite ease;
  		-webkit-animation: somecssload-thing_maybe_cool_idk 1.7s infinite ease;
  		-moz-animation: somecssload-thing_maybe_cool_idk 1.7s infinite ease;
  }
  .cssload-thing:nth-child(1) {
  	transform: rotate(30deg);
  		-o-transform: rotate(30deg);
  		-ms-transform: rotate(30deg);
  		-webkit-transform: rotate(30deg);
  		-moz-transform: rotate(30deg);
  	transform-origin: 50% -200%;
  		-o-transform-origin: 50% -200%;
  		-ms-transform-origin: 50% -200%;
  		-webkit-transform-origin: 50% -200%;
  		-moz-transform-origin: 50% -200%;
  	animation-delay: 0.2125s;
  		-o-animation-delay: 0.2125s;
  		-ms-animation-delay: 0.2125s;
  		-webkit-animation-delay: 0.2125s;
  		-moz-animation-delay: 0.2125s;
  }
  .cssload-thing:nth-child(2) {
  	transform: rotate(60deg);
  		-o-transform: rotate(60deg);
  		-ms-transform: rotate(60deg);
  		-webkit-transform: rotate(60deg);
  		-moz-transform: rotate(60deg);
  	transform-origin: 50% -200%;
  		-o-transform-origin: 50% -200%;
  		-ms-transform-origin: 50% -200%;
  		-webkit-transform-origin: 50% -200%;
  		-moz-transform-origin: 50% -200%;
  	animation-delay: 0.425s;
  		-o-animation-delay: 0.425s;
  		-ms-animation-delay: 0.425s;
  		-webkit-animation-delay: 0.425s;
  		-moz-animation-delay: 0.425s;
  }
  .cssload-thing:nth-child(3) {
  	transform: rotate(90deg);
  		-o-transform: rotate(90deg);
  		-ms-transform: rotate(90deg);
  		-webkit-transform: rotate(90deg);
  		-moz-transform: rotate(90deg);
  	transform-origin: 50% -200%;
  		-o-transform-origin: 50% -200%;
  		-ms-transform-origin: 50% -200%;
  		-webkit-transform-origin: 50% -200%;
  		-moz-transform-origin: 50% -200%;
  	animation-delay: 0.6375s;
  		-o-animation-delay: 0.6375s;
  		-ms-animation-delay: 0.6375s;
  		-webkit-animation-delay: 0.6375s;
  		-moz-animation-delay: 0.6375s;
  }
  .cssload-thing:nth-child(4) {
  	transform: rotate(120deg);
  		-o-transform: rotate(120deg);
  		-ms-transform: rotate(120deg);
  		-webkit-transform: rotate(120deg);
  		-moz-transform: rotate(120deg);
  	transform-origin: 50% -200%;
  		-o-transform-origin: 50% -200%;
  		-ms-transform-origin: 50% -200%;
  		-webkit-transform-origin: 50% -200%;
  		-moz-transform-origin: 50% -200%;
  	animation-delay: 0.85s;
  		-o-animation-delay: 0.85s;
  		-ms-animation-delay: 0.85s;
  		-webkit-animation-delay: 0.85s;
  		-moz-animation-delay: 0.85s;
  }
  .cssload-thing:nth-child(5) {
  	transform: rotate(150deg);
  		-o-transform: rotate(150deg);
  		-ms-transform: rotate(150deg);
  		-webkit-transform: rotate(150deg);
  		-moz-transform: rotate(150deg);
  	transform-origin: 50% -200%;
  		-o-transform-origin: 50% -200%;
  		-ms-transform-origin: 50% -200%;
  		-webkit-transform-origin: 50% -200%;
  		-moz-transform-origin: 50% -200%;
  	animation-delay: 1.0625s;
  		-o-animation-delay: 1.0625s;
  		-ms-animation-delay: 1.0625s;
  		-webkit-animation-delay: 1.0625s;
  		-moz-animation-delay: 1.0625s;
  }
  .cssload-thing:nth-child(6) {
  	transform: rotate(180deg);
  		-o-transform: rotate(180deg);
  		-ms-transform: rotate(180deg);
  		-webkit-transform: rotate(180deg);
  		-moz-transform: rotate(180deg);
  	transform-origin: 50% -200%;
  		-o-transform-origin: 50% -200%;
  		-ms-transform-origin: 50% -200%;
  		-webkit-transform-origin: 50% -200%;
  		-moz-transform-origin: 50% -200%;
  	animation-delay: 1.275s;
  		-o-animation-delay: 1.275s;
  		-ms-animation-delay: 1.275s;
  		-webkit-animation-delay: 1.275s;
  		-moz-animation-delay: 1.275s;
  }
  .cssload-thing:nth-child(7) {
  	transform: rotate(210deg);
  		-o-transform: rotate(210deg);
  		-ms-transform: rotate(210deg);
  		-webkit-transform: rotate(210deg);
  		-moz-transform: rotate(210deg);
  	transform-origin: 50% -200%;
  		-o-transform-origin: 50% -200%;
  		-ms-transform-origin: 50% -200%;
  		-webkit-transform-origin: 50% -200%;
  		-moz-transform-origin: 50% -200%;
  	animation-delay: 1.4875s;
  		-o-animation-delay: 1.4875s;
  		-ms-animation-delay: 1.4875s;
  		-webkit-animation-delay: 1.4875s;
  		-moz-animation-delay: 1.4875s;
  }
  .cssload-thing:nth-child(8) {
  	transform: rotate(240deg);
  		-o-transform: rotate(240deg);
  		-ms-transform: rotate(240deg);
  		-webkit-transform: rotate(240deg);
  		-moz-transform: rotate(240deg);
  	transform-origin: 50% -200%;
  		-o-transform-origin: 50% -200%;
  		-ms-transform-origin: 50% -200%;
  		-webkit-transform-origin: 50% -200%;
  		-moz-transform-origin: 50% -200%;
  	animation-delay: 1.7s;
  		-o-animation-delay: 1.7s;
  		-ms-animation-delay: 1.7s;
  		-webkit-animation-delay: 1.7s;
  		-moz-animation-delay: 1.7s;
  }
  .cssload-thing:nth-child(9) {
  	transform: rotate(270deg);
  		-o-transform: rotate(270deg);
  		-ms-transform: rotate(270deg);
  		-webkit-transform: rotate(270deg);
  		-moz-transform: rotate(270deg);
  	transform-origin: 50% -200%;
  		-o-transform-origin: 50% -200%;
  		-ms-transform-origin: 50% -200%;
  		-webkit-transform-origin: 50% -200%;
  		-moz-transform-origin: 50% -200%;
  	animation-delay: 1.9125s;
  		-o-animation-delay: 1.9125s;
  		-ms-animation-delay: 1.9125s;
  		-webkit-animation-delay: 1.9125s;
  		-moz-animation-delay: 1.9125s;
  }
  .cssload-thing:nth-child(10) {
  	transform: rotate(300deg);
  		-o-transform: rotate(300deg);
  		-ms-transform: rotate(300deg);
  		-webkit-transform: rotate(300deg);
  		-moz-transform: rotate(300deg);
  	transform-origin: 50% -200%;
  		-o-transform-origin: 50% -200%;
  		-ms-transform-origin: 50% -200%;
  		-webkit-transform-origin: 50% -200%;
  		-moz-transform-origin: 50% -200%;
  	animation-delay: 2.125s;
  		-o-animation-delay: 2.125s;
  		-ms-animation-delay: 2.125s;
  		-webkit-animation-delay: 2.125s;
  		-moz-animation-delay: 2.125s;
  }
  .cssload-thing:nth-child(11) {
  	transform: rotate(330deg);
  		-o-transform: rotate(330deg);
  		-ms-transform: rotate(330deg);
  		-webkit-transform: rotate(330deg);
  		-moz-transform: rotate(330deg);
  	transform-origin: 50% -200%;
  		-o-transform-origin: 50% -200%;
  		-ms-transform-origin: 50% -200%;
  		-webkit-transform-origin: 50% -200%;
  		-moz-transform-origin: 50% -200%;
  	animation-delay: 2.3375s;
  		-o-animation-delay: 2.3375s;
  		-ms-animation-delay: 2.3375s;
  		-webkit-animation-delay: 2.3375s;
  		-moz-animation-delay: 2.3375s;
  }
  .cssload-thing:nth-child(12) {
  	transform: rotate(360deg);
  		-o-transform: rotate(360deg);
  		-ms-transform: rotate(360deg);
  		-webkit-transform: rotate(360deg);
  		-moz-transform: rotate(360deg);
  	transform-origin: 50% -200%;
  		-o-transform-origin: 50% -200%;
  		-ms-transform-origin: 50% -200%;
  		-webkit-transform-origin: 50% -200%;
  		-moz-transform-origin: 50% -200%;
  	animation-delay: 2.55s;
  		-o-animation-delay: 2.55s;
  		-ms-animation-delay: 2.55s;
  		-webkit-animation-delay: 2.55s;
  		-moz-animation-delay: 2.55s;
  }

  @keyframes somecssload-thing_maybe_cool_idk {
  	50% {
  		background: rgb(187, 157, 19);
  		transform-origin: 50% -170%;
  	}
  }

  @-o-keyframes somecssload-thing_maybe_cool_idk {
  	50% {
  		background: rgb(187, 157, 19);
  		-o-transform-origin: 50% -170%;
  	}
  }

  @-ms-keyframes somecssload-thing_maybe_cool_idk {
  	50% {
  		background: rgb(187, 157, 19);
  		-ms-transform-origin: 50% -170%;
  	}
  }

  @-webkit-keyframes somecssload-thing_maybe_cool_idk {
  	50% {
  		background: rgb(187, 157, 19);
  		-webkit-transform-origin: 50% -170%;
  	}
  }

  @-moz-keyframes somecssload-thing_maybe_cool_idk {
  	50% {
  		background: rgb(187, 157, 19);
  		-moz-transform-origin: 50% -170%;
  	}
  }
</style>
