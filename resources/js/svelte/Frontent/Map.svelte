<!-- svelte-ignore missing-custom-element-compile-options -->
<svelte:options tag="map-view" />

<script>
    import * as axios from "axios";
    import * as L from "leaflet";
    import {onMount} from "svelte";

    let mapEditMode = false;
    let showAddEntryModal = false;
    let showDetailEntryModal = false;

    let addEntryAlert = {
        display: false,
        class: "alert-success",
        msg: ""
    };

    let map;
    let clickedPopup = new L.Popup();

    let offerCategories = [];

    let newEntry = {
        name: "",
        offer_category_id: 0,
        description: "",
        contact: "",
        lat: 0.0,
        lng: 0.0
    };

    let shownEntry = {
        id: 0,
        name: "Peter",
        category: "Kleidungerspende",
        description: "Ich spende Kleidung <b>bei</b> Vielmann!",
        contact: "Schreib mir per Insta @theodor.xyz",
        lat: 0.0,
        lng: 0.0
    };

    onMount(() => {
        fetchOfferCategories();

        map = L.map("map").setView([48.545705491847464, 10.634765625], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            'attribution':  'Kartendaten &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> Mitwirkende',
            'useCache': true
        }).addTo(map);

        map.on('click', onMapClick);

        let test1 = L.marker([52.35840924385216, 14.068679809570312], {
            title: "FW"
        }).addTo(map);

        test1.on("click", () => showDetailEntryModal = true);
    });

    function onMapClick(e) {
        let content = `
            Du hast hier geklickt. Möchtest du einen Eintrag erstellen?
            <button id="create-new-entry" class="badge bg-success">Erstellen</button>
        `;

        if (mapEditMode) {
            clickedPopup
                .setLatLng(e.latlng)
                .setContent(content)
                .openOn(map);

            newEntry.lat = e.latlng.lat;
            newEntry.lng = e.latlng.lng;

            document.getElementById("create-new-entry").addEventListener("click", () => showAddEntryModal = true);
        }

        console.log("POS", e.latlng);
    }

    function fetchOfferCategories() {
        axios.get("https://help-ukraine.ddev.site/api/offer-category/all")
            .then(response => {
                offerCategories = response.data.categories;
            });
    }

    function addNewEntry() {
        axios.post("https://help-ukraine.ddev.site/api/offer/create", newEntry)
            .then(response => {
                addEntryAlert.display = true;

                if (response.data.status === "ok") {
                    addEntryAlert.class = "alert-success";
                    addEntryAlert.msg = "Dein Angebot wurde hinzugefügt. Danke für deine Hilfe!";
                }

                if (response.data.status === "validation-error") {
                    addEntryAlert.class = "alert-danger";
                    addEntryAlert.msg = "Du hast Felder nicht korrekt ausgefüllt.";
                }

                setTimeout(() => addEntryAlert.display = false, 5000);
            });
    }
</script>

<div class="container bg-white">
    <b>Einstellungen</b>
    <div class="form-check form-switch">
        <input bind:checked={mapEditMode} class="form-check-input" type="checkbox" id="switchMapClick" name="mapClickType">
        <label class="form-check-label" for="switchMapClick">
            Hilfe anbieten / hinzufügen
        </label>
    </div>
</div>

<div id="map"></div>

{#if (showAddEntryModal)}
    <div class="modal d-block modal-background" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Punkt hinzufügen</h4>
                    <button on:click={() => showAddEntryModal = false} type="button" class="btn-close"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    {#if (addEntryAlert.display)}
                        <div class="alert {addEntryAlert.class}">{addEntryAlert.msg}</div>
                    {/if}

                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Dein Name</label>
                            <input bind:value={newEntry.name} type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="offer-category" class="form-label">Dein Angebot</label>
                            <select bind:value={newEntry.offer_category_id} id="offer-category" class="form-select" required>
                                {#each offerCategories as category (category.id)}
                                    <option value={category.id}>{category.name}</option>
                                {/each}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description">Beschreibung</label>
                            <textarea bind:value={newEntry.description} class="form-control" rows="4" id="description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="contact">Kontakt</label><br>
                            <small>Tipp: Erstellen Sie sich extra für dieses Angebot eine E-Mail oder so. Ihre Kontaktdaten sind öffentlich sichtbar.</small>
                            <textarea bind:value={newEntry.contact} class="form-control" rows="4" id="contact"></textarea>
                        </div>

                        <button on:click|preventDefault={addNewEntry} class="btn btn-outline-success">Einfügen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
{/if}

{#if (showDetailEntryModal)}
    <div class="modal d-block modal-background" id="myModal2">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Details</h4>
                    <button on:click={() => showDetailEntryModal = false} type="button" class="btn-close"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <b>Name</b>
                            <p>{shownEntry.name}</p>
                        </div>
                        <div class="mb-3">
                            <b>Angebot</b>
                            <p>{shownEntry.category}</p>
                        </div>

                        <div class="mb-3">
                            <b>Beschreibung</b>
                            <p>{shownEntry.description}</p>
                        </div>

                        <div class="mb-3">
                            <b>Kontakt</b>
                            <p>{shownEntry.contact}</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
{/if}

<style>
    #map {
        height: 100%;
        width: 100%;
    }

    .modal-background {
        background-color: rgba(0, 0, 7, 0.5);
    }
</style>
