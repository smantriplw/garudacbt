<?php

/*   ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
class Post_model extends CI_Model
{
    public function getPostUser($id_user)
    {
        $this->db->select("a.*, b.nama_guru, b.foto, (SELECT COUNT(post_comments.id_comment) FROM post_comments WHERE a.id_post = post_comments.id_post) AS jml");
        $this->db->from("post a");
        $this->db->join("master_guru b", "a.dari=b.id_guru", "left");
        if (!($id_user != 0)) {
            goto eron_;
        }
        $this->db->where("a.dari", $id_user);
        eron_:
        $this->db->order_by("a.updated", "desc");
        $posts = $this->db->get()->result();
        return $posts;
    }
    public function getPostForUser($kepada, $kelas = null)
    {
        $this->db->select("a.*, b.nama_guru, b.foto, (SELECT COUNT(post_comments.id_comment) FROM post_comments WHERE a.id_post = post_comments.id_post) AS jml");
        $this->db->from("post a");
        $this->db->join("master_guru b", "a.dari=b.id_guru", "left");
        if (!($kepada != null)) {
            goto mycvK;
        }
        $this->db->where("(a.kepada LIKE " . $kepada . ") OR (a.kepada LIKE " . $kelas . ")");
        mycvK:
        $this->db->order_by("a.updated", "desc");
        $posts = $this->db->get()->result();
        return $posts;
    }
    public function getIdComments($id_post)
    {
        $this->db->select("id_comment");
        $this->db->where("id_post", $id_post);
        $ids = $this->db->get("post_comments")->result();
        return $ids;
    }
}
