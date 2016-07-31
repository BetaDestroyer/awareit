<?php 

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_quiz")
 */
class UserQuiz 
{
	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $counter;

    /**
     * @ORM\Column(type="boolean", options={"default" : FALSE})
     */
    private $quizComplete;

    /**
     * @ORM\Column(type="integer", nullable = TRUE)
     */
    private $points;

    /**
     * @ORM\ManyToOne(targetEntity="user", inversedBy="userQuiz")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz", inversedBy="userQuiz")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $quiz;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set counter
     *
     * @param integer $counter
     *
     * @return UserQuiz
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;

        return $this;
    }

    /**
     * Get counter
     *
     * @return integer
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * Set quizComplete
     *
     * @param boolean $quizComplete
     *
     * @return UserQuiz
     */
    public function setQuizComplete($quizComplete)
    {
        $this->quizComplete = $quizComplete;

        return $this;
    }

    /**
     * Get quizComplete
     *
     * @return boolean
     */
    public function getQuizComplete()
    {
        return $this->quizComplete;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\user $user
     *
     * @return UserQuiz
     */
    public function setUser(\AppBundle\Entity\user $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set quiz
     *
     * @param \AppBundle\Entity\Quiz $quiz
     *
     * @return UserQuiz
     */
    public function setQuiz(\AppBundle\Entity\Quiz $quiz)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \AppBundle\Entity\Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return UserQuiz
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }
}
