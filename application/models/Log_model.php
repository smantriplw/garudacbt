<?php
/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
 class Log_model extends CI_Model { function __construct() { parent::__construct(); $this->load->library("\x75\x73\145\162\137\x61\x67\145\x6e\164"); } public function saveLog($type, $desc) { goto VxY3a; NdsKT: $os = $this->agent->platform(); goto djS49; VxY3a: $user_id = $this->ion_auth->user()->row()->id; goto mAGmQ; mAGmQ: $group = $this->ion_auth->get_users_groups($user_id)->row(); goto M6qQs; ssPbs: $agent = $this->agent->browser() . "\x20" . $this->agent->version(); goto sK9gN; M6qQs: if ($this->agent->is_browser()) { goto U_q9j; } goto G8rAK; rtdVX: goto tm1Ex; goto vsP4i; H1oWH: $agent = "\104\x61\164\141\x20\165\163\145\162\x20\x67\141\147\141\154\40\x64\151\40\x64\x61\160\x61\x74\153\x61\156"; goto rtdVX; djS49: $ip = $this->input->ip_address(); goto nsBPR; nsBPR: $this->insertLog($user_id, $group->id, $group->name, $type, $desc, $agent, $os, $ip); goto Q7k6I; vsP4i: U_q9j: goto ssPbs; xAB6_: tm1Ex: goto NdsKT; G8rAK: if ($this->agent->is_mobile()) { goto farfs; } goto H1oWH; sK9gN: goto tm1Ex; goto BTcH1; BTcH1: farfs: goto uPeoK; uPeoK: $agent = $this->agent->mobile(); goto xAB6_; Q7k6I: } private function insertLog($id_user, $group_id, $group_name, $type, $desc, $agent, $os, $ip) { $data = array("\151\x64\x5f\x75\163\x65\x72" => $id_user, "\151\x64\x5f\x67\162\x6f\x75\x70" => $group_id, "\156\x61\x6d\145\x5f\147\x72\157\x75\160" => $group_name, "\154\x6f\147\x5f\144\x65\x73\x63" => $desc, "\141\144\x64\x72\145\163\163" => $ip, "\x61\x67\x65\x6e\164" => $agent, "\144\145\166\x69\143\145" => $os); $this->db->insert("\154\157\x67", $data); } public function loadNotifikasi() { } public function loadChat() { } public function loadAktifitas($limit = null) { goto l9RK3; aYcS2: $this->db->join("\165\163\145\162\163\x20\x62", "\142\56\151\x64\75\141\x2e\x69\144\137\165\163\145\x72", "\x6c\x65\x66\x74"); goto pPWTE; KhTLR: if (!($limit != null)) { goto puemu; } goto cHZz0; pPWTE: $this->db->join("\147\x72\x6f\165\x70\163\x20\x64", "\144\56\151\x64\x3d\x61\x2e\x69\144\137\x67\x72\x6f\x75\x70"); goto KhTLR; cHZz0: $this->db->limit($limit, 0); goto ntIhE; l9RK3: $this->db->query("\x53\x45\x54\x20\123\x51\x4c\137\102\111\x47\x5f\x53\x45\114\x45\103\124\x53\x3d\x31"); goto txheu; txheu: $this->db->select("\141\x2e\52\x2c\40\x62\56\x66\x69\x72\x73\x74\x5f\x6e\x61\x6d\x65\x2c\x20\x62\56\x6c\x61\163\x74\x5f\x6e\x61\155\x65\x2c\x20\x64\x2e\156\141\x6d\x65"); goto bCKGy; EaDdm: $this->db->order_by("\x61\56\x6c\x6f\x67\x5f\164\x69\x6d\x65", "\x44\105\123\103"); goto CUu44; bCKGy: $this->db->from("\x6c\x6f\147\40\x61"); goto aYcS2; QPE33: return $result; goto Pa0jy; ntIhE: puemu: goto EaDdm; CUu44: $result = $this->db->get()->result(); goto QPE33; Pa0jy: } public function loadAktifitasSiswa($limit = null) { goto IxysK; HPUhr: $this->db->limit($limit, 0); goto sdMky; sdMky: khgZ1: goto Q2cCz; u0Inr: $result = $this->db->get()->result(); goto rrMR1; rrMR1: return $result; goto YCU1J; XqmZ2: $this->db->join("\165\163\x65\162\163\x20\x62", "\x62\56\x69\144\75\x61\56\151\144\x5f\165\x73\x65\x72", "\154\145\146\164"); goto ju12a; bSndA: $this->db->order_by("\x61\x2e\154\x6f\x67\x5f\x74\x69\155\x65", "\104\105\x53\103"); goto u0Inr; Q2cCz: $this->db->where("\141\x2e\x69\x64\x5f\147\x72\x6f\165\x70", "\x33"); goto bSndA; IxysK: $this->db->query("\x53\105\x54\40\x53\121\114\137\102\111\107\x5f\x53\105\x4c\105\x43\124\123\75\x31"); goto RiEP3; d9gly: if (!($limit != null)) { goto khgZ1; } goto HPUhr; lJt35: $this->db->from("\x6c\157\147\40\x61"); goto XqmZ2; RiEP3: $this->db->select("\x61\56\52\54\x20\x62\56\146\151\x72\163\164\137\156\141\x6d\145\x2c\x20\x62\56\154\x61\x73\164\137\x6e\x61\x6d\145\x2c\40\144\56\156\x61\155\145"); goto lJt35; ju12a: $this->db->join("\x67\x72\x6f\x75\x70\x73\x20\144", "\x64\x2e\x69\144\75\x61\56\151\x64\137\147\x72\x6f\x75\160"); goto d9gly; YCU1J: } }
