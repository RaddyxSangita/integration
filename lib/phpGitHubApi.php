<?php

require_once(dirname(__FILE__).'/request/phpGitHubApiRequest.php');

/**
 * Simple PHP GitHub API class.
 * Usage: http://wiki.github.com/ornicar/php-github-api/
 *
 * @author    Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @license   MIT License
 */
class phpGitHubApi
{
  protected $request  = null;
  protected $apis     = array();
  protected $debug;

  /**
   * Instanciate a new GitHub API
   *
   * @param  bool           $debug      print debug messages
   */
  public function __construct($debug = false)
  {
    $this->debug = $debug;
  }

  /**
   * Authenticate a user for all next requests
   *
   * @param  string         $login      GitHub username
   * @param  string         $token      GitHub private token
   * @return phpGitHubApi               fluent interface
   */
  public function authenticate($login, $token)
  {
    $this->getRequest()
    ->setOption('login', $login)
    ->setOption('token', $token);

    return $this;
  }

  /**
   * Deauthenticate a user for all next requests
   *
   * @return phpGitHubApi               fluent interface
   */
  public function deAuthenticate()
  {
    return $this->authenticate(null, null);
  }
  
  /**
   * Call any route, GET method
   * Ex: $api->get('repos/show/my-username/my-repo')
   *
   * @param   string  $route            the GitHub route
   * @param   array   $parameters       GET parameters
   * @return  array                     data returned
   */
  public function get($route, array $parameters = array())
  {
    return $this->getRequest()->get($route, $parameters);
  }

  /**
   * Call any route, POST method
   * Ex: $api->post('repos/show/my-username', array('email' => 'my-new-email@provider.org'))
   *
   * @param   string  $route            the GitHub route
   * @param   array   $parameters       POST parameters
   * @return  array                     data returned
   */
  public function post($route, array $parameters = array())
  {
    return $this->getRequest()->post($route, $parameters);
  }

  /**
   * Get the request
   *
   * @return  phpGitHubApiRequest   a request instance
   */
  protected function getRequest()
  {
    if(!isset($this->request))
    {
      $this->request = new phpGitHubApiRequest();
    }
    
    return $this->request;
  }

  /**
   * Inject another request
   *
   * @param   phpGitHubApiRequest   a request instance
   * @return  phpGitHubApi          fluent interface
   */
  protected function setRequest(phpGitHubApiRequest $request)
  {
    $this->request = $request;

    return $this;
  }

  /**
   * Get the user API
   *
   * @return  phpGitHubApiUser    the user API
   */
  public function getUserApi()
  {
    if(!isset($this->apis['user']))
    {
      require_once(dirname(__FILE__).'/apis/phpGitHubApiUser.php');
      $this->apis['user'] = new phpGitHubApiUser($this);
    }

    return $this->apis['user'];
  }

  /**
   * Get the issue API
   *
   * @return  phpGitHubApiIssue   the issue API
   */
  public function getIssueApi()
  {
    if(!isset($this->apis['issue']))
    {
      require_once(dirname(__FILE__).'/apis/phpGitHubApiIssue.php');
      $this->apis['issue'] = new phpGitHubApiIssue($this);
    }

    return $this->apis['issue'];
  }

  /**
   * Get the commit API
   *
   * @return  phpGitHubApiCommit  the commit API
   */
  public function getCommitApi()
  {
    if(!isset($this->apis['commit']))
    {
      require_once(dirname(__FILE__).'/apis/phpGitHubApiCommit.php');
      $this->apis['commit'] = new phpGitHubApiCommit($this);
    }

    return $this->apis['commit'];
  }

  /**
   * Get the object API
   *
   * @return  phpGitHubApiObject  the object API
   */
  public function getObjectApi()
  {
    if(!isset($this->apis['object']))
    {
      require_once(dirname(__FILE__).'/apis/phpGitHubApiObject.php');
      $this->apis['object'] = new phpGitHubApiObject($this);
    }

    return $this->apis['object'];
  }

  /**
   * Inject another api
   *
   * @param   string                $name the API name
   * @param   phpGitHubApiAbstract  $api  the API instance
   * @return  phpGitHubApi                fluent interface
   */
  public function setApi($name, phpGitHubApiAbstract $instance)
  {
    $this->apis[$name] = $instance;

    return $this;
  }

  /**
   * Get any API
   *
   * @param   string                $name the API name
   * @return  phpGitHubApiAbstract        the API instance
   */
  public function getApi($name)
  {
    return $this->apis[$name];
  }

  // DEPRECATED METHODS (BC COMPATIBILITY)

  /**
   * @deprecated  use ->getUserApi()->search()
   * @see         phpGitHubApiUser::search()
   */
  public function searchUsers($username)
  {
    return $this->getUserApi()->search($username);
  }

  /**
   * @deprecated  use ->getUserApi()->show()
   * @see         phpGitHubApiUser::show()
   */
  public function showUser($username)
  {
    return $this->getUserApi()->show($username);
  }

  /**
   * @deprecated  use ->getIssueApi()->getList()
   * @see         phpGitHubApiIssue::getList()
   */
  public function listIssues($username, $repo, $state = 'open')
  {
    return $this->getIssueApi()->getList($username, $repo, $state);
  }

  /**
   * @deprecated  use ->getIssueApi()->search()
   * @see         phpGitHubApiIssue::search()
   */
  public function searchIssues($username, $repo, $state, $searchTerm)
  {
    return $this->getIssueApi()->search($username, $repo, $state, $searchTerm);
  }

  /**
   * @deprecated  use ->getIssueApi()->show()
   * @see         phpGitHubApiIssue::show()
   */
  public function showIssue($username, $repo, $number)
  {
    return $this->getIssueApi()->show($username, $repo, $number);
  }

  /**
   * @deprecated  use ->getCommitApi()->getBranchCommits()
   * @see         phpGitHubApiCommit::getBranchCommits()
   */
  public function listBranchCommits($username, $repo, $branch)
  {
    return $this->getCommitApi()->getBranchCommits($username, $repo, $branch);
  }

  /**
   * @deprecated  use ->getCommitApi()->getFileCommits()
   * @see         phpGitHubApiCommit::getFileCommits()
   */
  public function listFileCommits($username, $repo, $branch, $path)
  {
    return $this->getCommitApi()->getFileCommits($username, $repo, $branch, $path);
  }

  /**
   * @deprecated  use ->getObjectApi()->showTree()
   * @see         phpGitHubApiObject::showTree()
   */
  public function listObjectTree($username, $repo, $treeSHA)
  {
    return $this->getObjectApi()->showTree($username, $repo, $treeSHA);
  }

  /**
   * @deprecated  use ->getObjectApi()->showBlob()
   * @see         phpGitHubApiObject::showBlob()
   */
  public function showObjectBlob($username, $repo, $treeSHA, $path)
  {
    return $this->getObjectApi()->showBlob($username, $repo, $treeSHA, $path);
  }

  /**
   * @deprecated  use ->getObjectApi()->listBlobs()
   * @see         phpGitHubApiObject::listBlobs()
   */
  public function listObjectBlobs($username, $repo, $treeSHA)
  {
    return $this->getObjectApi()->listBlobs($username, $repo, $treeSHA);
  }
}