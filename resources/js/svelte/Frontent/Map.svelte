<!-- svelte-ignore missing-custom-element-compile-options -->
<svelte:options tag="map-view" />

<script>
    import axios from "axios";
    import * as L from "leaflet";
    import {onMount} from "svelte";
    import {groupedMarkersByCategory, routeTo} from "../Helper/Config";
    import ReportForm from "./OfferReportForm.svelte";

    let mapEditMode = false;
    let showAddEntryModal = false;
    let showDetailEntryModal = false;

    let detailModalReport = {
        visibile: false,
        btnText: "Report",
    }

    let addEntryAlert = {
        display: false,
        class: "alert-success",
        msg: "",
        errors: [],
    };

    let map;
    let marker = [];
    let clickedPopup = new L.Popup();

    let offerCategories = [];

    let newEntry = {
        name: "",
        offer_category_id: 0,
        description: "",
        contact: "",
        visible_until: "",
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
        fetchMarkers();
        fetchOfferCategories();
    });

    function setupMap(points) {
        let categorys = groupedMarkersByCategory;

        marker.forEach(point => {
            let mkr = L.marker([point.lat, point.lng], {
                title: point.category.name
            }).on("click", () => updateDetailModal(point));

            //console.log("MOVE", mkr, "TO", categorys[point.category.id], categorys[point.category.id].name);
            categorys[point.category.id].marker.push(mkr);
        });

        let defaultMarkerGroups = [];
        let controlOverlay = {};

        for (const [key, value] of Object.entries(categorys)) {
            let markerGroup = L.layerGroup(value.marker);
            defaultMarkerGroups.push(markerGroup);
            controlOverlay[value.name] = markerGroup;
        }

        //console.log(defaultMarkerGroups, controlOverlay);

        map = L.map("map", {
            layers: defaultMarkerGroups
        }).setView([48.545705491847464, 10.634765625], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            'attribution':  'Mapdata &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            'useCache': true
        }).addTo(map);

        map.on('click', onMapClick);

        L.control.layers(null, controlOverlay, {collapsed:false}).addTo(map);
    }

    function onMapClick(e) {
        let content = `
            You clicked on this, do you wish to create a help offer?
            <button id="create-new-entry" class="badge bg-success">Create help offer</button>
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
        axios.get(routeTo("api/offer-category/all"))
            .then(response => {
                offerCategories = response.data.categories;
            });
    }

    function fetchMarkers() {
        axios.get(routeTo("api/offer/all-reviewed"))
            .then(response => {
                if (response.data.status === "ok") {
                    marker = response.data.offers;
                    //console.log("GETTED MARKERS", marker);
                    setupMap(marker);
                }
            });
    }

    //

    function updateDetailModal(offer) {
        shownEntry.id = offer.id;
        shownEntry.name = offer.name;
        shownEntry.description = offer.description;
        shownEntry.contact = offer.contact;
        shownEntry.category = offer.category.name;
        shownEntry.lat = offer.lat;
        shownEntry.lng = offer.lng;

        showDetailEntryModal = true;
    }

    function addNewEntry() {
        axios.post(routeTo("api/offer/create"), newEntry)
            .then(response => {
                addEntryAlert.display = true;

                if (response.data.status === "ok") {
                    addEntryAlert.class = "alert-success";
                    addEntryAlert.msg = "Your offer was added and will be reviewed by our moderation. After that, it will be public. Thank you alot for your help!";
                    addEntryAlert.errors = [];
                }

                if (response.data.status === "validation-error") {
                    addEntryAlert.class = "alert-danger";
                    addEntryAlert.msg = "The entered information is incomplete/incorrect";
                    addEntryAlert.errors = response.data.errors;
                }

                setTimeout(() => addEntryAlert.display = false, 10000);
            });
    }

    function toggleReportForm() {
        if (!detailModalReport.visibile) {
            detailModalReport.btnText = "Close report form";
            detailModalReport.visibile = true;
        } else {
            detailModalReport.btnText = "Report";
            detailModalReport.visibile = false;
        }
    }
</script>

<div class="container bg-white px-5">
    <b>Settings</b>
    <div class="form-check form-switch">
        <input bind:checked={mapEditMode} class="form-check-input" type="checkbox" id="switchMapClick" name="mapClickType">
        <label class="form-check-label" for="switchMapClick">
            Offer help
        </label>
    </div>
</div>

<div id="map"></div>

{#if (showAddEntryModal)}
    <div class="modal d-block modal-background" id="myModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add a new help offer</h4>
                    <button on:click={() => showAddEntryModal = false} type="button" class="btn-close"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    {#if (addEntryAlert.display)}
                        <div class="alert {addEntryAlert.class}">
                            {addEntryAlert.msg}
                            {#each addEntryAlert.errors as error (error)}
                                <ul>
                                    <li>Error: {error}</li>
                                </ul>
                            {/each}
                        </div>
                    {/if}

                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Your name</label>
                            <input bind:value={newEntry.name} type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="offer-category" class="form-label">Your offer</label>
                            <select bind:value={newEntry.offer_category_id} id="offer-category" class="form-select" required>
                                {#each offerCategories as category (category.id)}
                                    <option value={category.id}>{category.name}</option>
                                {/each}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description">Offer description</label>
                            <textarea bind:value={newEntry.description} class="form-control" rows="4" id="description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="contact">Contact</label><br>
                            <small>Tip: Use new/unique contact information for your help offers, the contact information entered will be publicly accessible!</small>
                            <textarea bind:value={newEntry.contact} class="form-control mt-2" rows="4" id="contact"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="visible_until">Visible on map until</label><br>
                            <input type="date" bind:value={newEntry.visible_until} class="form-control mt-2" id="visible_until" required/>
                        </div>

                        <button on:click|preventDefault={addNewEntry} class="btn btn-outline-success">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
{/if}

{#if (showDetailEntryModal)}
    <div class="modal d-block modal-background modal-dialog-centered" id="myModal2">
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
                            <b>Offer</b>
                            <p>{shownEntry.category}</p>
                        </div>

                        <div class="mb-3">
                            <b>Offer description</b>
                            <p>{shownEntry.description}</p>
                        </div>

                        <div class="mb-3">
                            <b>Contact</b>
                            <p>{shownEntry.contact}</p>
                        </div>
                    </form>

                    <a href="http://maps.google.de/maps?q={shownEntry.lat},{shownEntry.lng}&t=k&z=12"
                       target="_blank" class="btn btn-outline-success">Google Maps</a>

                    <button on:click|preventDefault={toggleReportForm}
                            class="btn btn-outline-danger">{detailModalReport.btnText}</button>

                    <div class="mt-2">
                        {#if detailModalReport.visibile}
                            <ReportForm offerId={shownEntry.id}/>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}

<style>
    #map {
        height: 89%;
        width: 100%;
    }

    .modal-background {
        background-color: rgba(0, 0, 7, 0.5);
    }
</style>
