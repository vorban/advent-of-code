package main

import (
	"errors"
	"log"
	"os"

	"github.com/joho/godotenv"
	"github.com/pterm/pterm"
)

/*
|--------------------------------------------------------------------------
| MARK: Public
|--------------------------------------------------------------------------
*/

type Credentials struct {
	Username   string
	Repository string
	Email      string
	Session    string
}

func GetCredentials() Credentials {
	err := godotenv.Load()
	if errors.Is(err, os.ErrNotExist) {
		f, _ := os.Create(".env")
		f.Close()
	} else if err != nil {
		log.Fatal("Error loading .env file.\n")
	}

	var username, repository, email, session string

	if username = os.Getenv("GITHUB_USERNAME"); username == "" {
		username, _ = pterm.DefaultInteractiveTextInput.Show("Enter your github.com username")
		updateEnv("GITHUB_USERNAME", username)
	}
	if repository = os.Getenv("GITHUB_REPOSITORY"); repository == "" {
		repository, _ = pterm.DefaultInteractiveTextInput.Show("Enter your github.com repository")
		updateEnv("GITHUB_REPOSITORY", repository)
	}
	if email = os.Getenv("GITHUB_EMAIL"); email == "" {
		email, _ = pterm.DefaultInteractiveTextInput.Show("Enter your github.com email")
		updateEnv("GITHUB_EMAIL", email)
	}
	if session = os.Getenv("AOC_SESSION"); session == "" {
		session, _ = pterm.DefaultInteractiveTextInput.WithMask("*").Show("Enter your adventofcode.com session ID")
		updateEnv("AOC_SESSION", session)
	}

	c := Credentials{username, repository, email, session}
	validateCredentials(c)
	return c
}

/*
|--------------------------------------------------------------------------
| MARK: Private
|--------------------------------------------------------------------------
*/

func validateCredentials(c Credentials) {
	if c.Session == "" {
		log.Fatal("Session ID is required\n")
	}
	if c.Username == "" {
		log.Fatal("Username is required\n")
	}
	if c.Repository == "" {
		log.Fatal("Repository is required\n")
	}
	if c.Email == "" {
		log.Fatal("Email is required\n")
	}
}

func updateEnv(key string, value string) {
	envFile, err := os.ReadFile(".env")
	if err != nil {
		log.Fatal("Could not read .env file.\n")
	}

	env, _ := godotenv.UnmarshalBytes(envFile)
	env[key] = value
	err = godotenv.Write(env, "./.env")
	if err != nil {
		log.Fatal("Could not write to .env file.\n")
	}
}
