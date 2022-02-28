import { readable } from 'svelte/store';
import axios from "axios";
import {routeTo} from "../Helper/Config";
export const availableMarker = readable(marker);
