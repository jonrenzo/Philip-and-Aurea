<?php
class MessageSystem{
    private $con;

    public function __construct($con){
        $this -> con = $con;
    }

    // Sending a Message
    public function sendMessage($user_id, $subject, $message) {
        $stmt = $this->con->prepare("
            INSERT INTO messages (user_id, subject, message)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("iss", $user_id, $subject, $message);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // get messages from users
    public function getMessages($user_id) {
        $messages = array();

        $stmt = $this->con->prepare("
            SELECT * FROM messages 
            WHERE user_id = ? 
            AND deleted_by_user = FALSE
            ORDER BY created_at DESC
        ");

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        $stmt->close();
        return $messages;
    }

    // receive messages from admin
    public function getAdminMessages() {
        $messages = array();

        try {
            $stmt = $this->con->prepare("
            SELECT m.*, u.uid, u.uemail 
            FROM messages m
            JOIN user u ON m.user_id = u.uid
            WHERE m.deleted_by_admin = FALSE
            ORDER BY 
                CASE m.status
                    WHEN 'unread' THEN 1
                    WHEN 'read' THEN 2
                    WHEN 'replied' THEN 3
                END,
                m.created_at DESC
        ");

            if (!$stmt) {
                error_log("Prepare failed: " . $this->con->error);
                return array();
            }

            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error);
                return array();
            }

            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }

            $stmt->close();
            return $messages;

        } catch (Exception $e) {
            error_log("Exception in getAdminMessages: " . $e->getMessage());
            return array();
        }
    }

    // mark messages as read
    public function markAsRead($message_id) {
        $stmt = $this->db->prepare("
            UPDATE messages 
            SET status = 'read' 
            WHERE id = ? 
            AND status = 'unread'
        ");
        $stmt->bind_param("i", $message_id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // delete message
    public function deleteMessage($message_id, $is_admin){
        $field = $is_admin ? 'deleted_by_admin' : 'deleted_by_user';
        $stmt = $this -> con -> prepare("UPDATE messages
                                         SET $field = TRUE
                                         WHERE id = ?");
        return $stmt -> execute([$message_id]);
    }

    // get unread count for admin
    public function getAdminUnreadCount() {
        $count = 0;

        $stmt = $this->con->prepare("
            SELECT COUNT(*) as count 
            FROM messages 
            WHERE status = 'unread' 
            AND deleted_by_admin = FALSE
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc()) {
            $count = $row['count'];
        }
        $stmt->close();
        return $count;
    }
}
