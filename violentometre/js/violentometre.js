document.addEventListener("DOMContentLoaded", function () {
  const startBtn = document.getElementById("start-quiz");
  const quizContainer = document.getElementById("violentometre-quiz");
  const startScreen = document.getElementById("violentometre-start");
  const questions = document.querySelectorAll(".quiz-question");
  const stepCounter = document.getElementById("step-counter");
  const resultContainer = document.getElementById("quiz-result");
  const resultText = document.getElementById("quiz-score-summary");

  let currentStep = 0;
  const totalSteps = questions.length;
  const answers = Array(totalSteps).fill(null);

  function showQuestion(index) {
    questions.forEach((q, i) => {
      q.style.display = i === index ? "block" : "none";
      q.classList.remove("fade-in");
    });

    questions[index].classList.add("fade-in");

    stepCounter.textContent =
      index === totalSteps - 1
        ? "Dernière question"
        : `Question ${index + 1} / ${totalSteps}`;
  }

  startBtn.addEventListener("click", function () {
    startScreen.style.display = "none";
    quizContainer.style.display = "block";
    quizContainer.classList.add("fade-in");
    showQuestion(0);

    // Suivi du lancement
    fetch(violentometreData.ajax_url, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        action: "quiz_track_launch",
      }),
    });
  });

  document.querySelectorAll(".quiz-answer-option").forEach((card) => {
    card.addEventListener("click", function () {
      const parent = this.closest(".quiz-question");
      const index = parseInt(parent.dataset.questionIndex);
      const score = parseInt(this.dataset.score);

      answers[index] = score;

      parent
        .querySelectorAll(".quiz-answer-option")
        .forEach((el) => el.classList.remove("selected"));
      this.classList.add("selected");

      if (index === totalSteps - 1) {
        setTimeout(() => {
          quizContainer.style.display = "none";
          resultContainer.style.display = "block";
          resultContainer.classList.add("fade-in");

          const total = answers.reduce(
            (acc, val) => acc + (Number.isInteger(val) ? val : 0),
            0
          );

          const bareme = violentometreData.baremes.find((item) => {
            return total >= parseInt(item.min) && total <= parseInt(item.max);
          });

          resultText.innerHTML = "";

          const scoreTitle = document.createElement("h2");
          scoreTitle.textContent = `Votre score : ${total} point${
            total > 1 ? "s" : ""
          }`;
          resultText.appendChild(scoreTitle);

          const messagePara = document.createElement("p");
          if (bareme) {
            messagePara.textContent = bareme.message
              .replace(/\\'/g, "'")
              .replace(/\\\\/g, "\\");
            resultContainer.classList.add(`zone-${bareme.zone.toLowerCase()}`);
          } else {
            messagePara.textContent = "Aucun barème correspondant trouvé.";
          }
          resultText.appendChild(messagePara);

          // Suivi du résultat
          if (total > 0) {
            fetch(violentometreData.ajax_url, {
              method: "POST",
              headers: {
                "Content-Type": "application/x-www-form-urlencoded",
              },
              body: new URLSearchParams({
                action: "quiz_track_result",
                score: total,
              }),
            });
          }
        }, 300);
      } else {
        currentStep = index + 1;
        showQuestion(currentStep);
      }
    });
  });

  document.querySelectorAll(".prev-question-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const parent = this.closest(".quiz-question");
      const index = parseInt(parent.dataset.questionIndex);
      if (index > 0) {
        currentStep = index - 1;
        showQuestion(currentStep);
      }
    });
  });

  document.querySelectorAll(".show-result-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const total = answers.reduce((acc, val) => {
        return acc + (Number.isInteger(val) ? val : 0);
      }, 0);

      quizContainer.style.display = "none";
      resultContainer.style.display = "block";

      const bareme = violentometreData.baremes.find((item) => {
        return total >= parseInt(item.min) && total <= parseInt(item.max);
      });

      resultText.innerHTML = "";

      const scoreTitle = document.createElement("h2");
      scoreTitle.textContent = `Votre score : ${total} point${
        total > 1 ? "s" : ""
      }`;
      resultText.appendChild(scoreTitle);

      const messagePara = document.createElement("p");
      if (bareme) {
        messagePara.textContent = bareme.message
          .replace(/\\'/g, "'")
          .replace(/\\\\/g, "\\");
        resultContainer.classList.add(`zone-${bareme.zone.toLowerCase()}`);
      } else {
        messagePara.textContent = "Aucun barème correspondant trouvé.";
      }
      resultText.appendChild(messagePara);

      // Suivi du résultat
      if (total > 0) {
        fetch(violentometreData.ajax_url, {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: new URLSearchParams({
            action: "quiz_track_result",
            score: total,
          }),
        });
      }
    });
  });
});
