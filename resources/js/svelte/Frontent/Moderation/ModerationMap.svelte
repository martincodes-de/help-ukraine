<!-- svelte-ignore missing-custom-element-compile-options -->
<svelte:options tag="moderation-map" />

<script>
    import * as L from "leaflet";
    import {onMount} from "svelte";

    onMount(() => {
        let lat = document.getElementById("lat").value;
        let lng = document.getElementById("lng").value;

        let map = L.map("map").setView([lat, lng], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            'attribution':  'Mapdata &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            'useCache': true
        }).addTo(map);

        L.marker({lat: lat, lng: lng}).addTo(map);
    });
</script>

<p>
    <b>Moderation map (simple as possible)</b>
    <small>To show the location only. No correct marker image or other functionallity included.</small>
</p>

<div id="map">test123</div>

<style>
    #map {
        height: 300px;
    }
</style>
