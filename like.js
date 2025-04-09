jQuery(document).ready(function ($) {
  console.log("like.js chargé");
  $("#like-button").on("click", function () {
    $.post(
      likeData.ajax_url,
      {
        action: "handle_like",
        post_id: likeData.post_id,
      },
      function (response) {
        if (response.success) {
          // Conditionner l'affichage en fonction du nombre de likes
          if (response.data > 1) {
            $("#like-count").html(
              response.data +
                ' personnes fières <span class="para">de ce mécénat !</span>'
            );
          } else if (response.data === 1) {
            $("#like-count").html(
              '1 personne fière <span class="para">de ce mécénat !</span>'
            );
          } else {
            $("#like-count").html(
              'Aucun vote <span class="para">Partagez cette page pour plus de votes !</span>'
            );
          }

          // Ajouter un message de remerciement si non présent
          if (!$("#thank-you-message").length) {
            $("<p>Merci pour votre vote !</p>")
              .attr("id", "thank-you-message")
              .addClass("thank_you_message para")
              .insertAfter("div.like-section > div");
          }

          // Mettre à jour le bouton pour indiquer que le vote a été enregistré
          $("#like-button")
            .attr("disabled", true)
            .addClass("disabled")
            .attr("aria-pressed", "true")
            .text("Like déjà enregistré");
        } else {
          alert("Vous avez déjà voté pour cette entreprise.");
        }
      }
    );
  });
});
