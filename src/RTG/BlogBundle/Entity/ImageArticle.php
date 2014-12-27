<?php

namespace RTG\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RTG\AppBundle\Entity\Image;

/**
 * @ORM\Entity
 * @ORM\Table("image_article")
 */
class ImageArticle extends Image
{

    public function __toString()
    {
        return (string) $this->id;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function storeFilenameForRemove()
    {
        $files = array();
        $files[] = $this->getAbsolutePath();
        $files[] = $this->getAbsoluteThumbPath('tb-48x48');
        $files[] = $this->getAbsoluteThumbPath('tb-128x128');
        $files[] = $this->getAbsoluteThumbPath('article-tb');
        $this->filenameForRemove = array();
        foreach ($files as $file) {
            if (file_exists($file)) {
                $this->filenameForRemove[] = $file;
            }
        }
    }

    public function removeUpload()
    {
        foreach ($this->filenameForRemove as $file) {
            unlink($file);
        }
    }

    protected function getUploadDir()
    {
        return 'uploads/blog/img/article';
    }

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

}
