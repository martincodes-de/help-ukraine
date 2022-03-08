import { readable } from 'svelte/store';
import * as L from "leaflet";
import {routeTo} from "../Helper/Config";
export const availableMarker = readable(marker);

let marker = {
    1: L.Icon({
        iconUrl: routeTo("img/map-marker/marker-house-v1.png")
    }),
    4: L.Icon({
        iconUrl: routeTo("img/map-marker/marker-etc-v1.png")
    }),
};
