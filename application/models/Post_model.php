<?php
/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
 class Post_model extends CI_Model { public function getPostUser($id_user) { goto bjVQm; qt52e: return $posts; goto SvYYm; HTQAQ: $this->db->join("\155\x61\x73\164\x65\x72\x5f\147\x75\162\165\40\142", "\141\56\144\x61\162\x69\75\x62\x2e\x69\144\x5f\x67\165\x72\x75", "\154\x65\146\x74"); goto qng_5; qng_5: if (!($id_user != 0)) { goto uhrOK; } goto b0g4c; bjVQm: $this->db->select("\141\x2e\x2a\x2c\40\x62\56\x6e\141\155\141\x5f\x67\x75\162\165\x2c\x20\x62\56\146\157\x74\157\54\40\x28\x53\x45\114\x45\103\x54\40\103\x4f\x55\x4e\124\50\x70\157\x73\164\137\x63\x6f\155\x6d\145\x6e\x74\x73\56\151\x64\x5f\143\157\155\155\x65\x6e\164\x29\40\106\122\x4f\115\x20\x70\157\163\x74\x5f\143\x6f\x6d\155\145\156\164\163\40\127\x48\105\122\x45\x20\141\x2e\x69\x64\137\160\x6f\163\x74\40\x3d\x20\x70\157\163\164\x5f\143\x6f\x6d\155\145\156\164\x73\x2e\x69\144\137\160\x6f\163\x74\51\x20\101\x53\x20\152\155\x6c"); goto aeaLL; u2EnH: $posts = $this->db->get()->result(); goto qt52e; qDVj3: uhrOK: goto OTny5; OTny5: $this->db->order_by("\141\56\165\x70\144\141\164\x65\x64", "\144\x65\x73\x63"); goto u2EnH; b0g4c: $this->db->where("\141\56\144\x61\162\x69", $id_user); goto qDVj3; aeaLL: $this->db->from("\x70\157\x73\164\40\141"); goto HTQAQ; SvYYm: } public function getPostForUser($kepada, $kelas = null) { goto n_pcJ; Ta7Eb: $posts = $this->db->get()->result(); goto iDlx9; iDlx9: return $posts; goto i73vF; guEXc: $this->db->where("\x28\141\56\153\145\160\141\x64\x61\40\114\111\113\x45\40" . $kepada . "\x29\40\117\122\x20\x28\141\x2e\153\x65\160\x61\144\x61\40\114\111\x4b\105\40" . $kelas . "\51"); goto IDdg_; FXgy7: $this->db->join("\155\x61\163\x74\145\162\137\x67\x75\x72\165\x20\142", "\141\56\x64\x61\162\151\75\142\56\151\144\x5f\x67\x75\x72\x75", "\x6c\x65\146\164"); goto R9cFN; lLqvl: $this->db->from("\160\x6f\x73\x74\x20\141"); goto FXgy7; IDdg_: UIIMs: goto jdqmL; n_pcJ: $this->db->select("\x61\x2e\52\54\40\142\56\x6e\x61\x6d\x61\x5f\147\165\x72\x75\54\40\142\56\146\157\164\x6f\x2c\40\x28\123\105\x4c\x45\103\124\40\x43\x4f\x55\x4e\x54\x28\160\x6f\163\164\x5f\143\x6f\155\x6d\145\x6e\164\163\56\x69\144\x5f\x63\x6f\155\155\x65\156\164\x29\40\106\122\117\x4d\40\160\157\x73\164\x5f\x63\157\155\x6d\x65\156\164\x73\x20\127\110\105\x52\105\x20\141\56\x69\144\137\x70\x6f\163\x74\40\x3d\40\x70\157\163\x74\x5f\143\x6f\155\x6d\145\x6e\x74\163\x2e\151\144\x5f\x70\157\163\x74\51\40\101\x53\40\152\155\x6c"); goto lLqvl; R9cFN: if (!($kepada != null)) { goto UIIMs; } goto guEXc; jdqmL: $this->db->order_by("\141\x2e\165\160\144\x61\164\x65\x64", "\144\x65\163\143"); goto Ta7Eb; i73vF: } public function getIdComments($id_post) { goto zShME; zShME: $this->db->select("\x69\144\x5f\x63\157\155\x6d\x65\156\164"); goto M84be; M84be: $this->db->where("\x69\144\137\160\157\163\x74", $id_post); goto Tfe72; Tfe72: $ids = $this->db->get("\x70\x6f\x73\x74\137\x63\x6f\x6d\x6d\x65\x6e\x74\163")->result(); goto NOTic; NOTic: return $ids; goto s5_eb; s5_eb: } public function getIdReplies($id_comment) { goto E_9Yh; E_9Yh: $this->db->select("\151\144\x5f\162\145\160\154\x79"); goto CHdl8; wU1_J: $this->db->where_in("\151\144\137\x63\157\155\155\145\156\x74", $id_comment); goto GjgOc; quw_V: cUfpf: goto wU1_J; GjgOc: gBgTO: goto u1ecX; CHdl8: if (is_array($id_comment)) { goto cUfpf; } goto UyXCV; UyXCV: $this->db->where("\151\x64\137\143\x6f\155\x6d\145\x6e\164", $id_comment); goto FWvLt; u1ecX: $ids = $this->db->get("\160\157\x73\164\137\x72\x65\160\154\171")->result(); goto MK7ge; MK7ge: return $ids; goto L2YyV; FWvLt: goto gBgTO; goto quw_V; L2YyV: } }
