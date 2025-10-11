import React from "react";
import ProjectCard from "../components/ProjectCard";
import Hero from "../components/Hero";
import About from "../components/About";
import Contact from "../components/Contact";
import styles from "../portfolio.module.css";
import { projects } from "../data/projects";

export default function Portfolio() {
  return (
    <main className={styles.container}>
      <Hero />
      <About />

      <section>
        <h2 style={{ margin: "16px 0" }}>Projects</h2>
        <div className={styles.grid}>
          {projects.map((p) => (
            <ProjectCard key={p.title} project={p} />
          ))}
        </div>
      </section>

      <Contact />
    </main>
  );
}
