<?php


namespace App\Models\contracts;


class EmailContract {
    protected $to;
    protected $message;

    /** structure is => [ ['data'=>'', 'name'=>''] ]  */
    protected $attachment = [];
    private $data;
    /**
     * @var mixed|null
     */
    private $subject;

    public function __construct( $data ) {
        $this->to = $data[ 'to' ] ?? null;
        $this->subject = $data[ 'subject' ] ?? null;
        $this->message = $data[ 'body' ] ?? null;
        foreach($data[ 'attachments' ] ?? [] as $attachment ){
            $this->attachment[] = new Attachment( $attachment[ 'name' ], $attachment[ 'data' ] );
        }
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getTo() {
        return $this->to;
    }

    /**
     * @return mixed|null
     */
    public function getSubject() {
        return $this->subject;
    }


    /**
     * @return mixed
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @return Attachment[]
     */
    public function getAttachment(): array {
        return $this->attachment;
    }


}
