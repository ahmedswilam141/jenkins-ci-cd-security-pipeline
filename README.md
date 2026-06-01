# Jenkins CI/CD Security Pipeline Project

## Project Overview

This project is a Jenkins-based CI/CD pipeline lab for a PHP service application.  
The goal of the lab is to build a complete automated pipeline that integrates:

- Private GitHub repository checkout using Jenkins credentials
- Unit testing with PHPUnit
- Static code analysis using SonarQube
- SonarQube Quality Gate enforcement
- Docker image build
- Container vulnerability scanning using Trivy
- Docker Hub image push using secure Jenkins credentials
- Local container deployment using Docker
- Final evidence screenshots for grading

The project demonstrates a practical DevOps workflow with security and quality checks before deployment.

---

## Repository Information

- Repository: `jenkins-ci-cd-security-pipeline`
- GitHub repository visibility: Public
- Application type: PHP CLI/Web service
- Docker image: `ahmedswilam12/service-app`
- Deployment port: `8081`

---

## Tools Used

| Tool | Purpose |
|---|---|
| Jenkins | CI/CD automation server |
| GitHub | Source code repository |
| GitHub PAT | Private repository authentication |
| PHPUnit | Unit testing |
| SonarQube | Static code analysis and quality gate |
| Docker | Containerization |
| Docker Hub | Container registry |
| Trivy | Docker image vulnerability scanning |
| zrok | Temporary public access to Jenkins, SonarQube, and deployed app |
| AWS EC2 | Remote Linux environment for running the lab |

---

## Pipeline Workflow

The Jenkins pipeline follows this order:

1. **Checkout**
   - Pulls the private GitHub repository using Jenkins credential ID `github-pat-creds`.

2. **Run Unit Tests**
   - Runs PHPUnit tests.
   - Publishes test results using Jenkins JUnit reports.

3. **SonarQube Analysis**
   - Sends the source code to SonarQube for static analysis.

4. **Quality Gate**
   - Waits for the SonarQube Quality Gate result.
   - Stops the pipeline if the Quality Gate fails.

5. **Build Docker Image**
   - Builds a Docker image using the repository Dockerfile.
   - Tags the image using the Jenkins build number.

6. **Trivy Scan**
   - Scans the built Docker image for critical vulnerabilities.
   - Runs after Docker build and before Docker Hub push.

7. **Push to Docker Hub**
   - Logs in to Docker Hub using Jenkins credential ID `dockerhub-creds`.
   - Pushes the image to Docker Hub with the build-number tag.

8. **Deploy**
   - Runs the application container locally on the EC2 server.
   - Exposes the application on port `8081`.

9. **Cleanup**
   - Removes the built Docker image.
   - Runs Docker image pruning inside a pipeline-level `post` block.

---

## Repository Structure

```text
service-app/
├── Dockerfile
├── Jenkinsfile
├── README.md
├── sonar-project.properties
├── src/
│   ├── index.php
│   ├── OrderProcessor.php
│   └── SubscriptionManager.php
├── tests/
│   ├── OrderTest.php
│   ├── SubscriptionTest.php
│   └── bootstrap.php
└── screenshots/
    ├── sonar-pass.png
    ├── sonar-dashboard.png
    ├── sonar-fail.png
    ├── dockerhub-push.png
    ├── trivy-scan.png
    ├── pipeline-success.png
    └── app-running.png
