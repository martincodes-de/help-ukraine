<!-- svelte-ignore missing-custom-element-compile-options -->
<svelte:options tag="offer-report-form" />

<script>
    import axios from "axios";
    import {routeTo} from "../Helper/Config";

    export let offerId;

    let alert = {
        visibile: false,
        class: "",
        message: "",
        errors: [],
    };

    let report = {
        offer_id: offerId,
        reason: ""
    }

    function sendReport() {
        axios.post(routeTo("api/offer/report"), report).then(response => {
            alert.visibile = true;

            if (response.data.status === "ok") {
                alert.class = response.data.alertClass;
                alert.message = response.data.alertMsg;
                alert.errors = [];
            } else if (response.data.status === "validation-error") {
                alert.class = response.data.alertClass;
                alert.message = response.data.alertMsg;
                alert.errors = response.data.errors;
            } else {
                alert.class = "alert-danger";
                alert.message = "Unknown error.";
            }

            setInterval(() => alert.visibile = false, 10000);
        });
    }
</script>

<div class="offer-report-form mt-2">
    {#if alert.visibile}
        <div class="alert {alert.class}">
            {alert.message}
            {#each alert.errors as error (error)}
                <ul>
                    <li>Error: {error}</li>
                </ul>
            {/each}
        </div>
    {/if}

    <form>
        <b>Report form</b>
        <p>Mit diesem Formular meldest du dieses Angebot an die Moderation des Projekts, wenn es gegen die Regeln verstößt
            oder dir anderweitig gegen den guten Willen gerichtet ist. Beschreibe dein Grund so genau wie möglich und
            hinterlass gerne Kontaktdaten, damit wir dich für Nachfragen erreichen können.</p>

        <div class="mb-3">
            <label for="report-description">Description</label>
            <textarea bind:value={report.reason} id="report-description" class="form-control" required></textarea>
        </div>

        <button on:click|preventDefault={sendReport} class="btn btn-success">Send report</button>
    </form>
</div>

<style>

</style>
