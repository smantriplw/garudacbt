<?php
/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
 goto YWvck; huUw3: exit("\116\157\40\x64\151\x72\145\x63\164\x20\x73\143\162\151\x70\164\x20\x61\143\x63\145\x73\163\x20\141\x6c\x6c\157\x77\145\144"); goto EuH_a; EuH_a: sd3J4: goto QrPJ_; YWvck: if (defined("\x42\x41\123\105\x50\101\124\x48")) { goto sd3J4; } goto huUw3; QrPJ_: class Compare extends CI_Controller { function __construct() { goto gJWCW; q3EDu: $this->DB1 = $this->load->database("\x6d\141\x69\156\137\147\141\x72\x75\144\x61", TRUE); goto RQBaP; RQBaP: $this->DB2 = $this->load->database("\x6c\x69\x76\x65", TRUE); goto Xgj9D; gJWCW: parent::__construct(); goto vuSeq; vuSeq: $this->CHARACTER_SET = "\165\x74\146\x38\40\103\117\114\114\x41\x54\105\x20\x75\x74\146\x38\x5f\x67\x65\156\145\162\141\154\137\x63\x69"; goto q3EDu; Xgj9D: } function index() { goto gOuPJ; UsEnu: $sql_commands_to_run = is_array($tables_to_update) && !empty($tables_to_update) ? array_merge($sql_commands_to_run, $this->update_existing_tables($tables_to_update)) : ''; goto E_hyc; ZGrKS: echo "\x3c\160\x3e\124\150\x65\x20\146\x6f\x6c\154\x6f\167\x69\x6e\x67\40\x53\121\x4c\40\x63\157\x6d\155\141\x6e\x64\x73\40\x6e\x65\145\x64\40\164\x6f\x20\x62\x65\x20\145\170\145\x63\x75\x74\145\144\x20\164\157\40\x62\162\151\x6e\x67\x20\164\150\x65\x20\114\x69\x76\x65\40\x64\x61\x74\x61\x62\141\x73\145\40\164\141\142\x6c\x65\163\x20\x75\160\40\164\157\40\144\x61\x74\145\72\40\74\x2f\160\76\12"; goto PW16x; Ngj35: WHaiO: goto EK6b7; me4CA: $development_tables = $this->DB1->list_tables(); goto Ge9za; JcQzP: echo "\74\150\62\76\124\x68\x65\40\144\141\x74\141\x62\141\163\x65\x20\151\163\x20\x6f\x75\x74\40\x6f\x66\40\123\171\156\143\x21\x3c\57\150\x32\x3e\12"; goto ZGrKS; n0A0p: dvYHF: goto sAqml; Rhfwj: $tables_to_create = array_diff($development_tables, $live_tables); goto CaQcu; gOuPJ: $sql_commands_to_run = array(); goto me4CA; Ts59i: $sql_commands_to_run = is_array($tables_to_drop) && !empty($tables_to_drop) ? array_merge($sql_commands_to_run, $this->manage_tables($tables_to_drop, "\144\x72\157\x70")) : array(); goto vuHNf; PW16x: echo "\74\160\x72\145\x20\163\164\171\154\145\75\47\160\x61\144\144\x69\156\147\x3a\x20\62\x30\160\x78\73\40\x62\x61\x63\x6b\147\162\x6f\165\x6e\x64\x2d\x63\157\x6c\157\162\x3a\40\43\x46\106\106\101\x46\60\x3b\x27\76\xa"; goto t0t3n; t0t3n: foreach ($sql_commands_to_run as $sql_command) { echo "{$sql_command}\12"; B9q25: } goto Ngj35; Tg90i: goto dvYHF; goto WdOVF; E_hyc: if (is_array($sql_commands_to_run) && !empty($sql_commands_to_run)) { goto O9t4W; } goto TmCA9; vk5hr: $tables_to_update = array_diff($tables_to_update, $tables_to_create); goto UsEnu; WdOVF: O9t4W: goto JcQzP; HsriM: $sql_commands_to_run = is_array($tables_to_create) && !empty($tables_to_create) ? array_merge($sql_commands_to_run, $this->manage_tables($tables_to_create, "\143\x72\145\141\x74\145")) : array(); goto Ts59i; CaQcu: $tables_to_drop = array_diff($live_tables, $development_tables); goto HsriM; vuHNf: $tables_to_update = $this->compare_table_structures($development_tables, $live_tables); goto vk5hr; Ge9za: $live_tables = $this->DB2->list_tables(); goto Rhfwj; TmCA9: echo "\74\150\x32\76\124\x68\145\x20\x64\141\164\x61\x62\141\x73\x65\x20\141\x70\x70\x65\141\162\163\x20\x74\157\x20\x62\145\40\x75\x70\x20\164\157\x20\144\x61\x74\145\x3c\x2f\150\x32\76\12"; goto Tg90i; EK6b7: echo "\x3c\x70\162\x65\x3e\12"; goto n0A0p; sAqml: } function manage_tables($tables, $action) { goto nt00C; Y88eC: foreach ($tables as $table) { $sql_commands_to_run[] = "\104\122\117\x50\40\x54\101\102\x4c\x45\40{$table}\x3b"; J4aTT: } goto IJsBe; m8ZPS: if (!($action == "\x63\162\x65\141\164\145")) { goto CKmoN; } goto bR3V7; E6G9B: WDm_H: goto WfQ_U; WfQ_U: CKmoN: goto gJnUZ; bR3V7: foreach ($tables as $table) { goto asVjZ; asVjZ: $query = $this->DB1->query("\123\x48\117\x57\x20\x43\122\x45\x41\124\x45\40\124\x41\x42\114\105\40\140{$table}\x60\40\x2d\x2d\40\143\x72\145\141\x74\x65\40\x74\141\142\x6c\x65\x73"); goto lxEpx; T0hfZ: $sql_commands_to_run[] = $table_structure["\103\162\x65\141\164\x65\40\x54\141\142\x6c\x65"] . "\x3b"; goto r2U93; r2U93: QphMI: goto SKr82; lxEpx: $table_structure = $query->row_array(); goto T0hfZ; SKr82: } goto E6G9B; gJnUZ: if (!($action == "\144\x72\x6f\160")) { goto iTag5; } goto Y88eC; Tnng9: iTag5: goto VFSVh; VFSVh: return $sql_commands_to_run; goto jHi1p; IJsBe: fcwdP: goto Tnng9; nt00C: $sql_commands_to_run = array(); goto m8ZPS; jHi1p: } function compare_table_structures($development_tables, $live_tables) { goto VtMx7; qBt0B: $live_table_structures = $development_table_structures = array(); goto zbF0f; VtMx7: $tables_need_updating = array(); goto qBt0B; yXvjf: return $tables_need_updating; goto q_5d7; IncVf: YrAN0: goto GcIcu; OQ7Qv: foreach ($development_tables as $table) { goto r5jT8; yYKri: $tables_need_updating[] = $table; goto UrySU; UrySU: x92QJ: goto JTf1B; SNhFo: if (!($this->count_differences($development_table, $live_table) > 0)) { goto x92QJ; } goto yYKri; r5jT8: $development_table = $development_table_structures[$table]; goto eN1AD; eN1AD: $live_table = isset($live_table_structures[$table]) ? $live_table_structures[$table] : ''; goto SNhFo; JTf1B: aRoY2: goto rzk3E; rzk3E: } goto ua8qk; vn89h: MIJXb: goto OQ7Qv; zbF0f: foreach ($development_tables as $table) { goto ZIYyt; av7Mr: $table_structure = $query->row_array(); goto oKFlv; oKFlv: $development_table_structures[$table] = $table_structure["\x43\x72\145\141\164\145\40\x54\x61\x62\x6c\x65"]; goto iXTGC; iXTGC: LcyzF: goto khY_R; ZIYyt: $query = $this->DB1->query("\x53\x48\x4f\x57\40\x43\x52\105\101\x54\105\40\x54\x41\x42\114\105\40\x60{$table}\x60\40\55\55\40\x64\x65\166"); goto av7Mr; khY_R: } goto IncVf; GcIcu: foreach ($live_tables as $table) { goto O6Nt1; aBZeh: $table_structure = $query->row_array(); goto LotB5; ETYfG: cUY0t: goto ImMXM; O6Nt1: $query = $this->DB2->query("\123\x48\x4f\127\40\103\x52\105\x41\x54\x45\x20\x54\x41\102\114\x45\40\140{$table}\x60\40\55\x2d\x20\x6c\x69\x76\145"); goto aBZeh; LotB5: $live_table_structures[$table] = $table_structure["\103\162\x65\x61\164\x65\40\124\141\x62\154\x65"]; goto ETYfG; ImMXM: } goto vn89h; ua8qk: IkwNd: goto yXvjf; q_5d7: } function count_differences($old, $new) { goto FQhXH; aTKiK: if (!($old[$i] != $new[$i])) { goto m32NP; } goto MuXdJ; LnlpA: C2rpk: goto sJO26; sJO26: $old = explode("\40", $old); goto GllgP; nQav9: if (!($old == $new)) { goto C2rpk; } goto v9vF9; aFhc1: no372: goto BAvaW; HKORD: cMoUH: goto O8nas; O2PwJ: $old = trim(preg_replace("\x2f\x5c\x73\53\x2f", '', $old)); goto ofyUZ; BAvaW: return $differences; goto M5psI; O8nas: $i++; goto frQJY; GllgP: $new = explode("\x20", $new); goto VEeXC; v9vF9: return $differences; goto LnlpA; iuQeZ: Q45v8: goto JvV2a; USNkl: $i = 0; goto iuQeZ; JvV2a: if (!($i < $length)) { goto no372; } goto aTKiK; RyC1F: m32NP: goto HKORD; frQJY: goto Q45v8; goto aFhc1; MuXdJ: $differences++; goto RyC1F; FQhXH: $differences = 0; goto O2PwJ; ofyUZ: $new = trim(preg_replace("\x2f\134\x73\53\57", '', $new)); goto nQav9; VEeXC: $length = max(count($old), count($new)); goto USNkl; M5psI: } function update_existing_tables($tables) { goto dgDM0; ZiAN9: return $sql_commands_to_run; goto wvzoj; YeyRV: if (!(is_array($tables) && !empty($tables))) { goto Egxih; } goto cRWGm; hywpK: Egxih: goto j_aiL; cRWGm: foreach ($tables as $table) { goto HUAWQ; sekLD: Ww0gF: goto YR6L6; HUAWQ: $table_structure_development[$table] = $this->table_field_data((array) $this->DB1, $table); goto fFopM; fFopM: $table_structure_live[$table] = $this->table_field_data((array) $this->DB2, $table); goto sekLD; YR6L6: } goto oJ32f; JiT5J: $table_structure_development = array(); goto Sfwuu; oJ32f: qpCaI: goto hywpK; Sfwuu: $table_structure_live = array(); goto YeyRV; dgDM0: $sql_commands_to_run = array(); goto JiT5J; j_aiL: $sql_commands_to_run = array_merge($sql_commands_to_run, $this->determine_field_changes($table_structure_development, $table_structure_live)); goto ZiAN9; wvzoj: } function table_field_data($database, $table) { goto UD33M; B3xtp: mhKSr: goto dgWBX; UD33M: $conn = mysqli_connect($database["\150\x6f\163\x74\156\141\x6d\145"], $database["\x75\x73\145\x72\156\141\x6d\145"], $database["\x70\141\163\x73\167\157\162\144"]); goto rVphm; fh98x: VAgmh: goto Bghnb; Bghnb: if (!($row = mysql_fetch_assoc($result))) { goto mhKSr; } goto iHDp6; dgWBX: return $fields; goto y4xUw; iHDp6: $fields[] = $row; goto hUlos; lfi3b: $result = mysql_query("\123\110\x4f\x57\40\103\x4f\114\125\115\x4e\x53\40\106\122\x4f\x4d\40\140{$table}\140"); goto fh98x; rVphm: mysql_select_db($database["\144\x61\164\x61\142\141\x73\x65"]); goto lfi3b; hUlos: goto VAgmh; goto B3xtp; y4xUw: } function determine_field_changes($source_field_structures, $destination_field_structures) { goto j3yQm; IzRyd: foreach ($source_field_structures as $table => $fields) { goto di3UO; tXoGv: qITMo: goto C4QXn; di3UO: foreach ($fields as $field) { goto SqK6M; ZZSXe: if (!($modify_field != '' && !in_array($modify_field, $sql_commands_to_run))) { goto WXVnK; } goto IQmE2; rLQ1_: goto g0LpY; goto uTBmx; FMkuk: tk2D2: goto L3Icb; i8UX0: j1lV_: goto ZZSXe; zhFJz: $add_field .= "\x20\104\x45\106\x41\125\114\x54\40" . $field["\104\x65\146\141\x75\x6c\x74"]; goto UUQbx; KYRcU: $n = 0; goto V_ZwG; GUyU6: $previous_field = $fields[$n]["\106\x69\145\154\144"]; goto i8UX0; Yd58o: tNaaz: goto BsYMm; uTBmx: pZegk: goto faBMu; MW4Wp: $sql_commands_to_run[] = $add_field; goto rLQ1_; yi5Ey: if (!(isset($fields[$n]) && isset($destination_field_structures[$table][$n]) && $fields[$n]["\x46\x69\x65\x6c\x64"] == $destination_field_structures[$table][$n]["\106\151\145\x6c\x64"])) { goto j1lV_; } goto i4eYu; u2No8: u8n5W: goto xX_ph; BsYMm: g0LpY: goto FMkuk; O8Dhq: $modify_field .= isset($fields[$n]["\104\x65\x66\141\165\x6c\164"]) && $fields[$n]["\104\x65\x66\141\x75\x6c\164"] != '' ? "\40\104\x45\106\x41\125\114\124\x20\x27" . $fields[$n]["\104\x65\x66\x61\165\x6c\x74"] . "\x27" : ''; goto odKwl; SqK6M: if ($this->in_array_recursive($field["\106\151\x65\x6c\144"], $destination_field_structures[$table])) { goto pZegk; } goto PnXBG; faBMu: $modify_field = ''; goto KYRcU; hCYK6: $modify_field .= isset($previous_field) && $previous_field != '' ? "\x20\101\x46\x54\105\122\x20" . $previous_field : ''; goto QQ4ud; oEynm: goto AqcKy; goto Yd58o; yu7nj: $add_field .= "\73"; goto MW4Wp; H3Cs7: WXVnK: goto u2No8; V_ZwG: AqcKy: goto VMi6P; IQmE2: $sql_commands_to_run[] = $modify_field; goto H3Cs7; qfiXd: $modify_field .= isset($fields[$n]["\105\x78\164\x72\x61"]) && $fields[$n]["\105\170\164\162\141"] != '' ? "\40" . $fields[$n]["\105\170\164\x72\x61"] : ''; goto hCYK6; UUQbx: $add_field .= isset($field["\105\170\164\x72\141"]) && $field["\105\170\164\162\x61"] != '' ? "\40" . $field["\105\x78\x74\x72\141"] : ''; goto yu7nj; PnXBG: $add_field = "\x41\114\124\105\122\40\x54\101\x42\114\x45\40{$table}\40\101\104\104\x20\x43\117\x4c\125\x4d\116\40\x60" . $field["\x46\151\145\x6c\x64"] . "\140\40" . $field["\x54\171\160\145"] . "\40\x43\x48\x41\x52\x41\x43\124\x45\x52\40\123\105\124\x20" . $this->CHARACTER_SET; goto mp3W6; QQ4ud: $modify_field .= "\73"; goto DuVUR; odKwl: $modify_field .= isset($fields[$n]["\116\x75\154\x6c"]) && $fields[$n]["\116\x75\x6c\154"] == "\x59\x45\x53" ? "\40\116\125\x4c\114" : "\40\116\117\x54\40\x4e\x55\x4c\114"; goto qfiXd; xX_ph: $n++; goto oEynm; mp3W6: $add_field .= isset($field["\116\x75\154\154"]) && $field["\116\x75\x6c\154"] == "\131\x45\x53" ? "\x20\x4e\165\154\x6c" : ''; goto zhFJz; c9bK1: if (!(is_array($differences) && !empty($differences))) { goto KQ_Pw; } goto EnCad; EnCad: $modify_field = "\x41\x4c\124\x45\122\40\124\x41\102\x4c\105\x20{$table}\x20\115\x4f\104\x49\x46\131\40\x43\117\114\x55\115\116\x20\x60" . $fields[$n]["\x46\151\145\x6c\144"] . "\140\x20" . $fields[$n]["\x54\171\x70\145"] . "\x20\103\x48\x41\122\x41\103\124\x45\122\40\x53\105\x54\x20" . $this->CHARACTER_SET; goto O8Dhq; VMi6P: if (!($n < count($fields))) { goto tNaaz; } goto yi5Ey; i4eYu: $differences = array_diff($fields[$n], $destination_field_structures[$table][$n]); goto c9bK1; DuVUR: KQ_Pw: goto GUyU6; L3Icb: } goto tXoGv; C4QXn: gKGqh: goto HL37G; HL37G: } goto jOGlB; fHY3E: return $sql_commands_to_run; goto phvLt; jOGlB: Bl710: goto fHY3E; j3yQm: $sql_commands_to_run = array(); goto IzRyd; phvLt: } function in_array_recursive($needle, $haystack, $strict = false) { goto Z5ftx; Z5ftx: foreach ($haystack as $array => $item) { goto Yd0dE; Yd0dE: $item = $item["\106\x69\145\x6c\x64"]; goto Ds80q; e00G2: er1e2: goto eNfxK; rp9Ol: return true; goto RqoDT; RqoDT: yk6DH: goto e00G2; Ds80q: if (!(($strict ? $item === $needle : $item == $needle) || is_array($item) && in_array_recursive($needle, $item, $strict))) { goto yk6DH; } goto rp9Ol; eNfxK: } goto icyVR; icyVR: AQl1x: goto pWhVd; pWhVd: return false; goto LdGiU; LdGiU: } }
