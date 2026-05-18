pipeline {
    agent any

    environment {
        DOCKERHUB_USERNAME = 'ahmedswilam12'
        IMAGE_NAME = "${DOCKERHUB_USERNAME}/service-app"
        IMAGE_TAG = "${BUILD_NUMBER}"
        FULL_IMAGE = "${IMAGE_NAME}:${IMAGE_TAG}"
        SONAR_SCANNER_HOME = tool 'SonarScanner'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout([
                    $class: 'GitSCM',
                    branches: [[name: '*/main']],
                    userRemoteConfigs: [[
                        url: 'https://github.com/ahmedswilam141/service-app.git',
                        credentialsId: 'github-pat-creds'
                    ]]
                ])
            }
        }

        stage('Run Unit Tests') {
            steps {
                sh '''
                    if command -v phpunit >/dev/null 2>&1; then
                        phpunit --log-junit results.xml tests
                    else
                        wget -q https://phar.phpunit.de/phpunit-9.phar -O phpunit
                        chmod +x phpunit
                        ./phpunit --log-junit results.xml tests
                    fi
                '''
            }
            post {
                always {
                    junit allowEmptyResults: true, testResults: 'results.xml'
                }
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarQube') {
                    sh '$SONAR_SCANNER_HOME/bin/sonar-scanner'
                }
            }
        }

        stage('Quality Gate') {
            steps {
                timeout(time: 10, unit: 'MINUTES') {
                    waitForQualityGate abortPipeline: true
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t "$FULL_IMAGE" .'
            }
        }

        stage('Trivy Scan') {
            steps {
                sh 'trivy image --exit-code 1 --severity CRITICAL "$FULL_IMAGE"'
            }
        }

        stage('Push to Docker Hub') {
            steps {
                withCredentials([
                    usernamePassword(
                        credentialsId: 'dockerhub-creds',
                        usernameVariable: 'DOCKERHUB_USER',
                        passwordVariable: 'DOCKERHUB_PASS'
                    )
                ]) {
                    sh '''
                        echo "$DOCKERHUB_PASS" | docker login -u "$DOCKERHUB_USER" --password-stdin
                        docker push "$FULL_IMAGE"
                    '''
                }
            }
        }

        stage('Deploy') {
            steps {
                sh '''
                    docker rm -f service-app || true
                    docker run -d --name service-app -p 8081:8080 "$FULL_IMAGE"
                    sleep 5
                    curl -f http://localhost:8081/
                '''
            }
        }
    }

    post {
        always {
            sh '''
                docker rmi -f "$FULL_IMAGE" || true
                docker image prune -f || true
            '''
        }
    }
}
